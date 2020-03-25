<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;

class CanSeeProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_see_profiles_test()
    {
        $this->withoutExceptionHandling();

        factory(User::class)->create(['name' => 'Elder']);

        $this->get('@Elder')->assertSee('Elder');
    }
}
