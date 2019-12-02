<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
