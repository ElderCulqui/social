<?php

namespace Tests\Feature;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

use App\Http\Resources\StatusResource;
use App\Events\StatusCreated;
use App\Models\Status;
use App\User;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    
    public function usuarios_invitados_no_pueden_crear_estados()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post(route('statuses.store'), ['body' => 'Mi primer status']);

        // dd($response->content());

        $response->assertRedirect('login');
    }

    /** @test */

    public function un_usuario_autenticado_puede_crear_estados()
    {   
        Event::fake([StatusCreated::class]);
        
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->post(route('statuses.store'), ['body' => 'Mi primer status']);

        $response->assertJson([
            'data' => ['body' => 'Mi primer status'] 
        ]);

        $this->assertDataBaseHas('statuses', [
            'user_id' => $user->id,
            'body' => 'Mi primer status'
        ]);
    }

    /** @test */
    public function an_event_is_fired_when_a_status_is_created()
    {
        Event::fake([StatusCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');
        
        $user = factory(User::class)->create();

        $this->actingAs($user)->post(route('statuses.store'), ['body' => 'Mi primer status']);

        Event::assertDispatched(StatusCreated::class, function($statusCreatedEvent) {
            $this->assertInstanceOf(ShouldBroadcast::class, $statusCreatedEvent);
            $this->assertInstanceOf(StatusResource::class, $statusCreatedEvent->status);
            $this->assertInstanceOf(Status::class, $statusCreatedEvent->status->resource);
            $this->assertEquals(Status::first()->id, $statusCreatedEvent->status->id);
            $this->assertEquals('socket-id', $statusCreatedEvent->socket, 'The event '. get_class($statusCreatedEvent) . ' must call the method "dontBroadcastToCurrentUser" in the constructor.');
            return true;
        });   
    }

    /** @test */

    public function estado_necesita_un_cuerpo()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }


    /** @test */

    public function el_cuerpo_de_un_estado_requiere_un_minimo_de_caracteres()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);


        $response = $this->postJson(route('statuses.store'), ['body' => 'as']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }
}
