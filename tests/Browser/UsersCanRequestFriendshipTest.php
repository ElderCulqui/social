<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\Friendship;
use App\User;

class UsersCanRequestFriendshipTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function senders_can_create_and_delete_friendship_requests()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                    ->visit(route('users.show', $recipient))
                    ->press('@request-friendship')
                    ->waitForText('Cancelar solicitud')
                    ->assertSee('Cancelar solicitud')
                    ->visit(route('users.show', $recipient))
                    ->assertSee('Cancelar solicitud')
                    ->press('@request-friendship')
                    ->waitForText('Solicitar amistad')
                    ->assertSee('Solicitar amistad')
                    ;
        });
    }
    
    /**
     * @test
     * @throws \Throwable
     */
    public function guests_cannot_create_friendship_requests()
    {
        $recipient = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($recipient) {
            $browser->visit(route('users.show', $recipient))
                    ->waitFor('@request-friendship')
                    ->pause(2000)
                    ->press('@request-friendship')
                    ->assertPathIs('/login')
                    ;   
        });
    }
    
    /**
     * @test
     * @throws \Throwable
     */
    public function a_user_cannot_send_friend_request_to_itself()
    {
        $sender = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($sender) {
            $browser->loginAs($sender)
                    ->visit(route('users.show', $sender))
                    ->assertMissing('@request-friendship')
                    ->assertSee('Eres tú')
                    ;
        });
    }
    
    /**
     * @test
     * @throws \Throwable
     */
    public function senders_can_delete_accepted_friendship_requests()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted',
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                    ->visit(route('users.show', $recipient))
                    ->waitForText('Eliminar de mis amigos')
                    ->assertSee('Eliminar de mis amigos')
                    ->press('@request-friendship')
                    ->waitForText('Solicitar amistad')
                    ->assertSee('Solicitar amistad')
                    ->visit(route('users.show', $recipient))
                    ->waitForText($sender->name)
                    ->waitForText('Solicitar amistad', 7)
                    ->assertSee('Solicitar amistad')
                    ;
        });
    }
    
    /**
     * @test
     * @throws \Throwable
     */
    public function senders_cannot_delete_denied_friendship_requests()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied',
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                    ->visit(route('users.show', $recipient))
                    ->waitForText('Solicitud denegada')
                    ->assertSee('Solicitud denegada')
                    ->press('@request-friendship')
                    ->waitForText('Solicitud denegada')
                    ->assertSee('Solicitud denegada')
                    ->visit(route('users.show', $recipient))
                    ->waitForText($sender->name)
                    ->waitForText('Solicitud denegada', 7)
                    ->assertSee('Solicitud denegada')
                    ;
        });
    }
    
    // /**
    //  * @test
    //  * @throws \Throwable
    //  */
    public function recipients_can_acepted_friendship_requests()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                    ->visit(route('accept-friendships.index'))
                    ->assertSee($sender->name)
                    ->press('@accept-friendship')
                    ->waitForText('son amigos', 7)
                    ->assertSee('son amigos')
                    ->visit(route('accept-friendships.index'))
                    ->assertSee('son amigos')
                    ;
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function recipients_can_deny_friendship_requests()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                    ->visit(route('accept-friendships.index'))
                    ->assertSee($sender->name)
                    ->press('@deny-friendship')
                    ->waitForText('Solicitud denegada')
                    ->assertSee('Solicitud denegada')
                    ->visit(route('accept-friendships.index'))
                    ->assertSee('Solicitud denegada')
                    ;
        });
    }
    
    /**
     * @test
     * @throws \Throwable
     */
    public function recipients_can_delete_friendship_requests()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                    ->visit(route('accept-friendships.index'))
                    ->assertSee($sender->name)
                    ->press('@delete-friendship')
                    ->waitForText('Solicitud eliminada')
                    ->assertSee('Solicitud eliminada')
                    ->visit(route('accept-friendships.index'))
                    ->assertDontSee('Solicitud eliminada')
                    ->assertDontSee($sender->name)
                    ;
        });
    }

}
