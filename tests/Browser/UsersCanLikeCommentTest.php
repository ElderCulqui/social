<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\User;
use App\Models\Comment;

class UsersCanLikeCommentTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function users_can_like_and_unlike_comments()
    {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create();
        
        $this->browse(function (Browser $browser) use ($user,$comment) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->waitForText($comment->body)
                    ->assertSeeIn('@comment-likes-count', 0)
                    ->press('@comment-like-btn')
                    ->waitForText('TE GUSTA')
                    ->assertSee('TE GUSTA')
                    ->assertSeeIn('@comment-likes-count', 1)
                    
                    ->press('@comment-like-btn')
                    ->waitForText('ME GUSTA')
                    ->assertSee('ME GUSTA')
                    ->pause(1000)
                    ->waitFor('@comment-likes-count')
                    ->assertSeeIn('@comment-likes-count', 0)
                    ;
        });
    }

    /**
     * A Dusk test example.
     * @test
     * @throws \Throwable
     */
    public function users_can_see_likes_in_real_time()
    {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create();
        
        $this->browse(function (Browser $browser1, Browser $browser2) use ($user,$comment) {
            
            $browser1->visit('/');

            $browser2->loginAs($user)
                    ->visit('/')
                    ->waitForText($comment->body)
                    ->assertSeeIn('@comment-likes-count', 0)
                    ->press('@comment-like-btn')
                    ->waitForText('TE GUSTA')
                    ;

            $browser1->pause(1000)->assertSeeIn('@comment-likes-count', 1);

            $browser2->press('@comment-like-btn')
                     ->waitForText('ME GUSTA')
            ;

            $browser1->waitFor('@comment-likes-count')
                     ->pause(2000)
                     ->assertSeeIn('@comment-likes-count', 0);
        });
    }
}
