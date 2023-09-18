<?php

namespace Tests\Feature;

use App\Models\Relay;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\RelaysData;

class RelayTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_relays_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/relays');

        $response->assertStatus(200);
    }

    public function test_get_relay_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/relays/create');

        $response->assertStatus(200);
    }

    public function test_get_relay_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new RelaysData();

        $data = $generator->generateRelay();

        $response = $this->actingAs($user)->get('/relays/'.$data['relay']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_relay_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/relays', [
            'name' => 'Создание тестового реле',
            'type' => 'relay',
            'device_id' => null,
            'port_id' => null,
            'hitepro_devices' => null,
            'place' => null,
        ]);

        $relay = Relay::where('name', 'Создание тестового реле')->first();

        $response->assertRedirect('/relays/'.($relay ? $relay->id : 1).'/edit');

        $this->assertDatabaseHas('relays', [
            'name' => 'Создание тестового реле',
        ]);
    }

    public function test_post_relay_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new RelaysData();

        $data = $generator->generateRelay();

        $response = $this->actingAs($user)->post('relays/'.$data['relay']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового реле',
            'id_object' => $data['object']->id,
            'device_id' => null,
            'port_id' => null,
            'alice_command' => null,
            'room' => 0,
            'event_idobject' => null,
            'hitepro_devices' => null,
            'place' => null,
        ]);

        $response->assertRedirect('/relays/'.$data['relay']->id.'/edit');

        $this->assertDatabaseHas('relays', [
            'name' => 'Обновление тестового реле',
        ]);
    }

    public function test_delete_relay_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new RelaysData();

        $data = $generator->generateRelay();

        $response = $this->actingAs($user)
            ->postJson('relays/delete', ['id' => $data['relay']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('relays', [
            'name' => 'Тестовое реле',
        ]);
    }
}
