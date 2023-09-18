<?php

namespace Tests\Feature;

use App\Models\Dimmer;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\DimmersData;

class DimmerTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_dimmers_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/dimmers');

        $response->assertStatus(200);
    }

    public function test_get_dimmer_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/dimmers/create');

        $response->assertStatus(200);
    }

    public function test_get_dimmer_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DimmersData();

        $data = $generator->generateDimmer();

        $response = $this->actingAs($user)->get('/dimmers/'.$data['dimmer']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_dimmer_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/dimmers', [
            'name' => 'Создание тестового диммера',
            'device_id' => null,
            'port_id' => null,
            'value' => 10,
            'speed' => 10,
        ]);

        $dimmer = Dimmer::where('name', 'Создание тестового диммера')->first();

        $response->assertRedirect('/dimmers/'.($dimmer ? $dimmer->id : 1).'/edit');

        $this->assertDatabaseHas('dimmers', [
            'name' => 'Создание тестового диммера',
        ]);
    }

    public function test_post_dimmer_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DimmersData();

        $data = $generator->generateDimmer();

        $response = $this->actingAs($user)->post('dimmers/'.$data['dimmer']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового диммера',
            'id_object' => $data['object']->id,
            'device_id' => null,
            'port_id' => null,
            'value' => 12,
            'speed' => 12,
            'alice_command' => null,
            'room' => 0,
            'event_idobject' => $data['object']->id,
        ]);

        $response->assertRedirect('/dimmers/'.$data['dimmer']->id.'/edit');

        $this->assertDatabaseHas('dimmers', [
            'name' => 'Обновление тестового диммера',
        ]);
    }

    public function test_delete_dimmer_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DimmersData();

        $data = $generator->generateDimmer();

        $response = $this->actingAs($user)
            ->postJson('dimmers/delete', ['id' => $data['dimmer']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('dimmers', [
            'name' => 'Тестовый диммер',
        ]);
    }
}
