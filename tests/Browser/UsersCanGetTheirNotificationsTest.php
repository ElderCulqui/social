<?php

namespace Tests\Browser;

use App\User;
use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanGetTheirNotificationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function  users_can_see_their_notifications_in_the_navbar()
    {
        $user = factory(User::class)->create();
        $status = factory(Status::class)->create();

        $notification = factory(DatabaseNotification::class)->create([
            'notifiable_id' => $user->id,
            'data' => [
                'message' => 'Has recibido un like',
                'link' => route('statuses.show', $status)
            ]
        ]);

        $this->browse(function (Browser $browser) use ($user, $notification, $status) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->click('@notifications')
                    ->assertSee('Has recibido un like')
                    ->click("@{$notification->id}")
                    ->assertUrlIs($status->path())

                    ->click('@notifications')
                    ->press("@mark-as-read-{$notification->id}")
                    ->waitFor("@mark-as-unread-{$notification->id}")
                    ->assertMissing("@mark-as-read-{$notification->id}")

                    ->press("@mark-as-unread-{$notification->id}")
                    ->waitFor("@mark-as-read-{$notification->id}")
                    ->assertMissing("@mark-as-unread-{$notification->id}")
                    ;
        });
    }

    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function users_can_see_their_like_notifications_in_real_time()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $status = factory(Status::class)->create(['user_id' => $user1->id]);

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user1, $user2, $status) {
            $browser1->loginAs($user1)
                    ->visit('/')
                    ;

            $browser2->loginAs($user2)
                    ->visit('/')
                    ->press('@like-btn')
                    ->pause(2000)
                    ;
            
            $browser1->waitFor('@notifications-count')->assertSeeIn('@notifications-count', 1);
        });
    }

    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function users_can_see_their_comment_notifications_in_real_time()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $status = factory(Status::class)->create(['user_id' => $user1->id]);

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user1, $user2, $status) {
            $browser1->loginAs($user1)
                    ->visit('/')
                    ;

            $browser2->loginAs($user2)
                    ->visit('/')
                    ->type('comment','Mi primer comentario')
                    ->press('@comment-btn')
                    ->pause(2000)
                    ;
            
            $browser1->waitFor('@notifications-count')
                     ->pause(1000)
                     ->assertSeeIn('@notifications-count', 1)
                     ;
        });
    }
}