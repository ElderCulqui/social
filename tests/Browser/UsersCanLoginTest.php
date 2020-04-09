<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class UsersCanLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function usuarios_registrados_pueden_logearse()
    {
        factory(User::class)->create([
            'email' => 'jorge@email.com'
        ]);


        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email','jorge@email.com')
                    ->type('password','password')
                    ->press('@login-btn')
                    ->assertPathIs('/')
                    ->assertAuthenticated();
        });
    }

    /**
     * @test void
     * @throws \Throwable
     */
    public function user_cannot_login_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', '')
                    ->press('@login-btn')
                    ->assertPathIs('/login')
                    ->assertPresent('@validation-errors');
                    ;

        });
    }
}
