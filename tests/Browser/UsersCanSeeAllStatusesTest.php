<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Status;

class UsersCanSeeAllStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_ver_todos_los_estados_en_la_homepage()
    {
        $statuses = factory(Status::class, 3)->create(['created_at' => now()->subMinute()]);

        $this->browse(function (Browser $browser) use ($statuses) {
            $browser->visit('/')
                    ->waitForText($statuses->first()->body)
                    ->assertSee($statuses->first()->body)
                    ;
            
            foreach ($statuses as $key => $status) {
                $browser->assertSee($status->body)
                        ->assertSee($status->user->name)
                        ->assertSee($status->created_at->diffForHumans())
                        ;
            }
        });

    }
}
