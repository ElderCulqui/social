<?php

namespace Tests\Unit\Http\Resources;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Http\Resources\CommentResource;
use App\Http\Resources\StatusResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Status;
use App\User;

class StatusResourceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_status_resources_must_have_the_necessary_fields()
    {
        $status = factory(Status::class)->create();

        factory(Comment::class)->create(['status_id' => $status->id]);

        $statusResource = StatusResource::make($status)->resolve();

        // dd($statusResource);
        $this->assertEquals(
            $status->id, 
            $statusResource['id']
        );
        
        $this->assertEquals(
            $status->body, 
            $statusResource['body']
        );

        $this->assertEquals(
            $status->created_at->diffForHumans(), 
            $statusResource['ago']
        );

        $this->assertEquals(
            false,
            $statusResource['is_liked']
        );
        
        $this->assertEquals(
            false,
            $statusResource['likes_count']
        );
        
        // dd($statusResource['comments']->first()->resource);

        $this->assertEquals(
            CommentResource::class,
            $statusResource['comments']->collects
        );

        $this->assertInstanceOf(
            Comment::class,
            $statusResource['comments']->first()->resource
        );

        // dd($statusResource['user']);
        $this->assertInstanceOf(
            UserResource::class,
            $statusResource['user']
        );

        $this->assertInstanceOf(
            User::class,
            $statusResource['user']->resource
        );
    }
}
