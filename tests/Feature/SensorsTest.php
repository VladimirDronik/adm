<?php

namespace Tests\Feature;

use App\Models\Hygrostat;
use App\Models\Lightstat;
use App\Models\Motionsensor;
use App\Models\Termostat;
use App\User;
use Database\Seeders\Tests\HygrostatSeeder;
use Database\Seeders\Tests\LightstatSeeder;
use Database\Seeders\Tests\MotionsensorSeeder;
use Database\Seeders\Tests\TermostatSeeder;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SensorsTest extends TestCase
{
    use RefreshDatabase;
    protected $seeder = TestAdminSeeder::class;

    public function test_get_termostats_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/termostats');

        $response->assertStatus(200);
    }

    public function test_get_termostat_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/termostats/create');

        $response->assertStatus(200);
    }

    public function test_get_termostat_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(TermostatSeeder::class);

        $termostat = Termostat::where('name', 'Тестовый Термостат')->first();

        $response = $this->actingAs($user)->get("/termostats/$termostat->id/edit");

        $response->assertStatus(200);
    }

    public function test_post_termostat_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/termostats', [
            'name' => 'Создание тестового термостата',
            'placetype' => 'port',
            'device_id' => null,
            'thermostat' => '1',
            'optimal' => '22',
            'gisteresis' => '1',
            'min_threshold' => '0',
            'max_threshold' => '30',
            'min_alarm' => '0',
            'max_alarm' => '40',
        ]);

        $termostat = Termostat::where('name', 'Создание тестового термостата')->first();

        $response->assertRedirect('/termostats/'. ($termostat ? $termostat->id : 1) .'/edit');

        $this->assertDatabaseHas('termostats', [
            'name' => 'Создание тестового термостата',
        ]);
    }

    public function test_post_termostat_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(TermostatSeeder::class);

        $termostat = Termostat::where('name', 'Тестовый Термостат')->first();

        $response = $this->actingAs($user)->post("termostats/$termostat->id", [
            '_method' => 'PUT',
            'name' => 'Обновление тестового термостата',
            'placetype' => 'port',
            'id_object' => $termostat->id_object,
            'device_id' => null,
            'port_id' => null,
            'room' => 0,
            'thermostat' => '1',
            'optimal' => '22',
            'gisteresis' => '1',
            'min_threshold' => '0',
            'max_threshold' => '30',
            'min_alarm' => '0',
            'max_alarm' => '40',
        ]);

        $response->assertRedirect("/termostats/$termostat->id/edit");

        $this->assertDatabaseHas('termostats', [
            'name' => 'Обновление тестового термостата',
        ]);
    }

    public function test_delete_termostat_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(TermostatSeeder::class);

        $termostat = Termostat::where('name', 'Тестовый Термостат')->first();

        $response = $this->actingAs($user)
            ->postJson('termostats/delete', ['id' => $termostat->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('termostats', [
            'name' => 'Тестовый Термостат',
        ]);
    }

    public function test_get_hygrostats_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/hygrostats');

        $response->assertStatus(200);
    }

    public function test_get_hygrostat_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/hygrostats/create');

        $response->assertStatus(200);
    }

    public function test_get_hygrostat_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(HygrostatSeeder::class);

        $hygrostat = Hygrostat::where('name', 'Тестовый Гигростат')->first();

        $response = $this->actingAs($user)->get("/hygrostats/$hygrostat->id/edit");

        $response->assertStatus(200);
    }

    public function test_post_hygrostat_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/hygrostats', [
            'name' => 'Создание тестового гигростата',
            'placetype' => 'port',
            'device_id' => null,
            'type' => '1',
            'optimal' => '22',
            'gisteresis' => '1',
            'min_threshold' => '0',
            'max_threshold' => '30',
            'min_alarm' => '0',
            'max_alarm' => '40',
        ]);

        $hygrostat = Hygrostat::where('name', 'Создание тестового гигростата')->first();

        $response->assertRedirect('/hygrostats/'. ($hygrostat ? $hygrostat->id : 1) .'/edit');

        $this->assertDatabaseHas('hygrostats', [
            'name' => 'Создание тестового гигростата',
        ]);
    }

    public function test_post_hygrostat_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(HygrostatSeeder::class);

        $hygrostat = Hygrostat::where('name', 'Тестовый Гигростат')->first();

        $response = $this->actingAs($user)->post("hygrostats/$hygrostat->id", [
            '_method' => 'PUT',
            'name' => 'Обновление тестового гигростата',
            'placetype' => 'usensor',
            'id_object' => $hygrostat->id_object,
            'room' => 0,
            'type' => '1',
            'optimal' => '22',
            'gisteresis' => '1',
            'min_threshold' => '0',
            'max_threshold' => '30',
            'min_alarm' => '0',
            'max_alarm' => '40',
        ]);

        $response->assertRedirect("/hygrostats/$hygrostat->id/edit");

        $this->assertDatabaseHas('hygrostats', [
            'name' => 'Обновление тестового гигростата',
        ]);
    }

    public function test_delete_hygrostat_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(HygrostatSeeder::class);

        $hygrostat = Hygrostat::where('name', 'Тестовый Гигростат')->first();

        $response = $this->actingAs($user)
            ->postJson('hygrostats/delete', ['id' => $hygrostat->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('hygrostats', [
            'name' => 'Тестовый Гигростат',
        ]);
    }

    public function test_get_lightstats_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/lightstats');

        $response->assertStatus(200);
    }

    public function test_get_lightstat_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/lightstats/create');

        $response->assertStatus(200);
    }

    public function test_get_lightstat_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(LightstatSeeder::class);

        $lightstat = Lightstat::where('name', 'Тестовый Светостат')->first();

        $response = $this->actingAs($user)->get("/lightstats/$lightstat->id/edit");

        $response->assertStatus(200);
    }

    public function test_post_lightstat_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/lightstats', [
            'name' => 'Создание тестового светостата',
            'placetype' => 'port',
            'device_id' => null,
            'mode' => '1',
            'optimal' => '22',
            'gisteresis' => '1',
            'min_threshold' => '0',
            'max_threshold' => '30',
            'min_alarm' => '0',
            'max_alarm' => '40',
        ]);

        $lightstat = Lightstat::where('name', 'Создание тестового светостата')->first();

        $response->assertRedirect('/lightstats/'. ($lightstat ? $lightstat->id : 1) .'/edit');

        $this->assertDatabaseHas('lightstats', [
            'name' => 'Создание тестового светостата',
        ]);
    }

    public function test_post_lightstat_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(LightstatSeeder::class);

        $lightstat = Lightstat::where('name', 'Тестовый Светостат')->first();

        $response = $this->actingAs($user)->post("lightstats/$lightstat->id", [
            '_method' => 'PUT',
            'name' => 'Обновление тестового светостата',
            'placetype' => 'port',
            'id_object' => $lightstat->id_object,
            'device_id' => null,
            'port_SCL' => null,
            'port_SDA' => null,
            'room' => 0,
            'mode' => '1',
            'optimal' => '22',
            'gisteresis' => '1',
            'min_threshold' => '0',
            'max_threshold' => '30',
            'min_alarm' => '0',
            'max_alarm' => '40',
        ]);

        $response->assertRedirect("/lightstats/$lightstat->id/edit");

        $this->assertDatabaseHas('lightstats', [
            'name' => 'Обновление тестового светостата',
        ]);
    }

    public function test_delete_lightstat_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(LightstatSeeder::class);

        $lightstat = Lightstat::where('name', 'Тестовый Светостат')->first();

        $response = $this->actingAs($user)
            ->postJson('lightstats/delete', ['id' => $lightstat->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('lightstats', [
            'name' => 'Тестовый Светостат',
        ]);
    }

    public function test_get_motionsensors_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/motionsensors');

        $response->assertStatus(200);
    }

    public function test_get_motionsensor_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/motionsensors/create');

        $response->assertStatus(200);
    }

    public function test_get_motionsensor_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(MotionsensorSeeder::class);

        $motionsensor = Motionsensor::where('name', 'Тестовый Датчик движения')->first();

        $response = $this->actingAs($user)->get("/motionsensors/$motionsensor->id/edit");

        $response->assertStatus(200);
    }

    public function test_post_motionsensor_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/motionsensors', [
            'name' => 'Создание тестового Датчика движения',
            'device_id' => null,
            'port_id' => null,
        ]);

        $motionsensor = Motionsensor::where('name', 'Создание тестового Датчика движения')->first();

        $response->assertRedirect('/motionsensors/'. ($motionsensor ? $motionsensor->id : 1) .'/edit');

        $this->assertDatabaseHas('motionsensors', [
            'name' => 'Создание тестового Датчика движения',
        ]);
    }

    public function test_post_motionsensor_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(MotionsensorSeeder::class);

        $motionsensor = Motionsensor::where('name', 'Тестовый Датчик движения')->first();

        $response = $this->actingAs($user)->post("motionsensors/$motionsensor->id", [
            '_method' => 'PUT',
            'name' => 'Обновление тестового Датчика движения',
            'id_object' => $motionsensor->id_object,
            'port_id' => null,
        ]);

        $response->assertRedirect("/motionsensors/$motionsensor->id/edit");

        $this->assertDatabaseHas('motionsensors', [
            'name' => 'Обновление тестового Датчика движения',
        ]);
    }

    public function test_delete_motionsensor_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $this->seed(MotionsensorSeeder::class);

        $motionsensor = Motionsensor::where('name', 'Тестовый Датчик движения')->first();

        $response = $this->actingAs($user)
            ->postJson('motionsensors/delete', ['id' => $motionsensor->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('motionsensors', [
            'name' => 'Тестовый Датчик движения',
        ]);
    }
}
