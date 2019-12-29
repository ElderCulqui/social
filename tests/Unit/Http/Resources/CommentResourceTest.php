<?php

namespace Tests\Unit\Http\Resources;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Status;
use App\Http\Resources\CommentResource;

class CommentResourceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_comment_resources_must_have_the_necessary_fields()
    {
        $comment = factory(Status::class)->create();

        $commentResource = CommentResource::make($comment)->resolve();

        $this->assertEquals(
            $comment->body, 
            $commentResource['body']
        );
        
        $this->assertEquals(
            $comment->user->name, 
            $commentResource['user_name']
        );
        
        $this->assertEquals(
            'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcT8wsXoAeQVQpY2jv1uekQY5FOffdqL_stDYTYfBkmV1Q4zuN0I',
            $commentResource['user_avatar']
        );
    }
}
