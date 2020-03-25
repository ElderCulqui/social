<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanRegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test void
     * @throws \Throwable
     */
    public function user_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'ElderCulqui')
                    ->type('first_name', 'Elder')
                    ->type('last_name', 'Culqui')
                    ->type('email', 'elderculqui@email.com')
                    ->type('password', 'secret')
                    ->type('password_confirmation', 'secret')
                    ->press('@register-btn')
                    ->assertPathIs('/')
                    ->assertAuthenticated();
                    ;

        });
    }

    /**
     * @test void
     * @throws \Throwable
     */
    public function user_cannot_register_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', '')
                    ->press('@register-btn')
                    ->assertPathIs('/register')
                    ->assertPresent('@validation-errors');
                    ;

        });
    }
}
