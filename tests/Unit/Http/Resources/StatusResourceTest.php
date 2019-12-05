<?php

namespace Tests\Unit\Http\Resources;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Status;
use App\Http\Resources\StatusResource;

class StatusResourceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_status_resources_must_have_the_necessary_fields()
    {
        $status = factory(Status::class)->create();

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
            $status->user->name, 
            $statusResource['user_name']
        );

        $this->assertEquals(
            'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcT8wsXoAeQVQpY2jv1uekQY5FOffdqL_stDYTYfBkmV1Q4zuN0I', 
            $statusResource['user_avatar']
        );

        $this->assertEquals(
            $status->created_at->diffForHumans(), 
            $statusResource['ago']
        );

        $this->assertEquals(
            false,
            $statusResource['is_liked']
        );
    }
}
