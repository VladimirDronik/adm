<?php

namespace Tests\Feature;

use App\Models\Lamp;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\LampsData;

class LampTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_lamps_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/lamps');

        $response->assertStatus(200);
    }

    public function test_get_lamp_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/lamps/create');

        $response->assertStatus(200);
    }

    public function test_get_lamp_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new LampsData();

        $data = $generator->generateLamp();

        $response = $this->actingAs($user)->get('/lamps/'.$data['lamp']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_lamp_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/lamps', [
            'name' => 'Создание тестовой лампы',
            'device_id' => null,
            'port_id' => null,
            'hitepro_devices' => null,
            'place' => null,
        ]);

        $lamp = Lamp::where('name', 'Создание тестовой лампы')->first();

        $response->assertRedirect('/lamps/'.($lamp ? $lamp->id : 1).'/edit');

        $this->assertDatabaseHas('lamps', [
            'name' => 'Создание тестовой лампы',
        ]);
    }

    public function test_post_lamp_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new LampsData();

        $data = $generator->generateLamp();

        $response = $this->actingAs($user)->post('lamps/'.$data['lamp']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестовой лампы',
            'id_object' => $data['object']->id,
            'device_id' => null,
            'port_id' => null,
            'hitepro_devices' => null,
            'place' => null,
            'alice_command' => null,
            'room' => 0,
            'event_idobject' => $data['object']->id,
        ]);

        $response->assertRedirect('/lamps/'.$data['lamp']->id.'/edit');

        $this->assertDatabaseHas('lamps', [
            'name' => 'Обновление тестовой лампы',
        ]);
    }

    public function test_delete_lamp_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new LampsData();

        $data = $generator->generateLamp();

        $response = $this->actingAs($user)
            ->postJson('lamps/delete', ['id' => $data['lamp']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('lamps', [
            'name' => 'Тестовая лампа',
        ]);
    }
}
