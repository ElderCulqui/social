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
                    
                    ->press('@comment-unlike-btn')
                    ->waitForText('ME GUSTA')
                    ->assertSee('ME GUSTA')
                    ->waitFor('@comment-likes-count')
                    ->assertSeeIn('@comment-likes-count', 0)
                    ;
        });
    }
}
