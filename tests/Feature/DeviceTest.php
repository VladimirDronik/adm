<?php

namespace Tests\Feature;

use App\Models\Device;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\DevicesData;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_devices_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/devices');

        $response->assertStatus(200);
    }

    public function test_get_device_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/devices/create');

        $response->assertStatus(200);
    }

    public function test_get_device_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DevicesData();

        $data = $generator->generateDevice();

        $response = $this->actingAs($user)->get('/devices/'.$data['device']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_device_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DevicesData();

        $data = $generator->generateDevice();

        $response = $this->actingAs($user)->post('/devices', [
            'description' => 'Создание тестового контроллера',
            'type' => $data['dev_type']->name,
            'ip_address' => '161.165.20.179',
            'password' => 'sec',
        ]);

        $device = Device::where('description', 'Создание тестового контроллера')->first();

        $response->assertRedirect('/devices/'.($device ? $device->id : 1).'/edit');

        $this->assertDatabaseHas('devices', [
            'description' => 'Создание тестового контроллера',
        ]);
    }

    public function test_post_device_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DevicesData();

        $data = $generator->generateDevice();

        $response = $this->actingAs($user)->postJson('devices/update', [
            'id' => $data['device']->id,
            'description' => 'Обновление тестового контроллера',
            'ip_address' => '161.165.20.179',
            'password' => '123',
            'port' => null,
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseHas('devices', [
            'description' => 'Обновление тестового контроллера',
        ]);
    }

    public function test_delete_device_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DevicesData();

        $data = $generator->generateDevice();

        $response = $this->actingAs($user)
            ->postJson('devices/delete', ['id' => $data['device']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('devices', [
            'description' => 'Тестовый контроллер',
        ]);
    }
}
