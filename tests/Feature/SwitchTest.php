<?php

namespace Tests\Feature;

use App\Models\DeviceSwitch;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\SwitchesData;

class SwitchTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_switches_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/switches');

        $response->assertStatus(200);
    }

    public function test_get_switch_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/switches/create');

        $response->assertStatus(200);
    }

    public function test_get_switch_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SwitchesData();

        $data = $generator->generateSwitch();

        $response = $this->actingAs($user)->get('/switches/'. $data['switch']->id .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_switch_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/switches', [
            'type' => 'switch',
            'name' => 'Создание тестового выключателя',
            'device_id' => null,
            'port_id' => null,
            'hitepro_devices' => null,
            'object' => null,
            'method' => null,
            'method_params' => null,
            'object_dc' => null,
            'method_dc' => null,
            'method_dc_params' => null,
            'object_lc' => null,
            'method_lc' => null,
            'method_lc_params' => null,
            'place' => null,
        ]);

        $switch = DeviceSwitch::where('name', 'Создание тестового выключателя')->first();

        $response->assertRedirect('/switches/'. ($switch ? $switch->id : 1) .'/edit');

        $this->assertDatabaseHas('switches', [
            'name' => 'Создание тестового выключателя',
        ]);
    }

    public function test_post_switch_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SwitchesData();

        $data = $generator->generateSwitch();

        $response = $this->actingAs($user)->post('switches/'. $data['switch']->id, [
            '_method' => 'PUT',
            'type' => 'switch',
            'name' => 'Обновление тестового выключателя',
            'id_object' => $data['object']->id,
            'device_id' => null,
            'port_id' => null,
            'hitepro_devices' => null,
            'object' => null,
            'method' => null,
            'method_params' => null,
            'object_dc' => null,
            'method_dc' => null,
            'method_dc_params' => null,
            'object_lc' => null,
            'method_lc' => null,
            'method_lc_params' => null,
            'event_idobject' => null,
            'place' => null,
        ]);

        $response->assertRedirect('/switches/'. $data['switch']->id .'/edit');

        $this->assertDatabaseHas('switches', [
            'name' => 'Обновление тестового выключателя',
        ]);
    }

    public function test_delete_switch_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SwitchesData();

        $data = $generator->generateSwitch();

        $response = $this->actingAs($user)
            ->postJson('switches/delete', ['id' => $data['switch']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('switches', [
            'name' => 'Тестовый выключатель',
        ]);
    }
}
