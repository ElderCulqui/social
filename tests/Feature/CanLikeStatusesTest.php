<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\Models\Status;

class CanLikeStatusesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_can_not_like_statuses()
    {
        // $this->withoutExceptionHandling();

        $status = factory(Status::class)->create();

        $response = $this->post(route('statuses.likes.store', $status));

        // dd($response->content());

        $response->assertRedirect('login');
    }

    /** @test */
    public function an_authenticate_user_can_like_and_unlike_statuses()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $status = factory(Status::class)->create();

        $this->assertCount(0, $status->likes);

        $response = $this->actingAs($user)->postJson( route('statuses.likes.store', $status) );

        $response->assertJsonFragment([
            'likes_count' => 1
        ]);

        $this->assertCount(1, $status->fresh()->likes);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson( route('statuses.likes.destroy', $status) );

        $response->assertJsonFragment([
            'likes_count' => 0
        ]);

        $this->assertCount(0, $status->fresh()->likes);

        $this->assertDatabaseMissing('likes', ['user_id' => $user->id]);
    }
}
