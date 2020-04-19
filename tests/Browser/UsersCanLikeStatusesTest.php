<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use App\Models\Status;

class UsersCanLikeStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function guest_users_cannot_like_statuses()
    {
        $status = factory(Status::class)->create(['created_at' => now()->subHour()]);
        
        $this->browse(function (Browser $browser) use ($status) {
            $browser->visit('/')
                    ->waitForText($status->body)
                    ->waitFor('@like-btn')
                    ->press('@like-btn')
                    ->waitForLocation('/login')
                    ->assertPathIs('/login')
                    ;
        });
    }

    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function users_can_like_and_unlike_statuses()
    {
        $user = factory(User::class)->create();
        $status = factory(Status::class)->create();
        
        $this->browse(function (Browser $browser) use ($user,$status) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->waitForText($status->body)
                    ->assertSeeIn('@likes-count', 0)
                    ->press('@like-btn')
                    ->waitForText('TE GUSTA')
                    ->assertSee('TE GUSTA')
                    ->pause(1000)
                    ->waitFor('@likes-count')
                    ->assertSeeIn('@likes-count', 1)
                    
                    ->press('@like-btn')
                    ->waitForText('ME GUSTA')
                    ->assertSee('ME GUSTA')
                    ->pause(1000)
                    ->waitFor('@likes-count')
                    ->assertSeeIn('@likes-count', 0)
                    ;
        });
    }
    
    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function users_can_see_likes_and_unlikes_in_real_time()
    {
        $user = factory(User::class)->create();
        $status = factory(Status::class)->create();
        
        $this->browse(function (Browser $browser1, Browser $browser2) use ($user,$status) {
            
            $browser1->visit('/');

            $browser2->loginAs($user)
                    ->visit('/')
                    ->waitForText($status->body)
                    ->assertSeeIn('@likes-count', 0)
                    ->press('@like-btn')
                    ->waitForText('TE GUSTA')
                    ;

            $browser1->assertSeeIn('@likes-count', 1);

            $browser2->press('@like-btn')
                    ->waitForText('ME GUSTA')
            ;

            $browser1->assertSeeIn('@likes-count', 0);
        });
    }
}
