<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Models\Friendship;

class CanSeeFriendsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_access_the_list_of_friends()
    {
        $this->get(route('friends.index'))->assertRedirect('login');
    }

    /** @test*/
    public function a_user_can_see_a_list_of_friends()
    {
        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        factory(Friendship::class)->create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted'
        ]);

        $this->actingAs($sender)->get(route('friends.index'))->assertSee($recipient->name);
        $this->actingAs($recipient)->get(route('friends.index'))->assertSee($sender->name);
    }
}
