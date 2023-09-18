<?php

namespace Tests\Feature;

use App\Models\Carbmonoxide;
use App\Models\Drycontact;
use App\Models\Hygrostat;
use App\Models\Lightstat;
use App\Models\Motionsensor;
use App\Models\Termostat;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\SensorsData;

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

        $generator = new SensorsData();

        $data = $generator->generateTermostat();

        $response = $this->actingAs($user)->get('/termostats/'.$data['termostat']->id.'/edit');

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

        $response->assertRedirect('/termostats/'.($termostat ? $termostat->id : 1).'/edit');

        $this->assertDatabaseHas('termostats', [
            'name' => 'Создание тестового термостата',
        ]);
    }

    public function test_post_termostat_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateTermostat();

        $response = $this->actingAs($user)->post('termostats/'.$data['termostat']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового термостата',
            'placetype' => 'port',
            'id_object' => $data['termostat']->id_object,
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

        $response->assertRedirect('/termostats/'.$data['termostat']->id.'/edit');

        $this->assertDatabaseHas('termostats', [
            'name' => 'Обновление тестового термостата',
        ]);
    }

    public function test_delete_termostat_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateTermostat();

        $response = $this->actingAs($user)
            ->postJson('termostats/delete', ['id' => $data['termostat']->id], ['X-Requested-With' => 'XMLHttpRequest']);

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

        $generator = new SensorsData();

        $data = $generator->generateHygrostat();

        $response = $this->actingAs($user)->get('/hygrostats/'.$data['hygrostat']->id.'/edit');

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

        $response->assertRedirect('/hygrostats/'.($hygrostat ? $hygrostat->id : 1).'/edit');

        $this->assertDatabaseHas('hygrostats', [
            'name' => 'Создание тестового гигростата',
        ]);
    }

    public function test_post_hygrostat_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateHygrostat();

        $response = $this->actingAs($user)->post('hygrostats/'.$data['hygrostat']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового гигростата',
            'placetype' => 'usensor',
            'id_object' => $data['hygrostat']->id_object,
            'room' => 0,
            'type' => '1',
            'optimal' => '22',
            'gisteresis' => '1',
            'min_threshold' => '0',
            'max_threshold' => '30',
            'min_alarm' => '0',
            'max_alarm' => '40',
        ]);

        $response->assertRedirect('/hygrostats/'.$data['hygrostat']->id.'/edit');

        $this->assertDatabaseHas('hygrostats', [
            'name' => 'Обновление тестового гигростата',
        ]);
    }

    public function test_delete_hygrostat_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateHygrostat();

        $response = $this->actingAs($user)
            ->postJson('hygrostats/delete', ['id' => $data['hygrostat']->id], ['X-Requested-With' => 'XMLHttpRequest']);

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

        $generator = new SensorsData();

        $data = $generator->generateLightstat();

        $response = $this->actingAs($user)->get('/lightstats/'.$data['lightstat']->id.'/edit');

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

        $response->assertRedirect('/lightstats/'.($lightstat ? $lightstat->id : 1).'/edit');

        $this->assertDatabaseHas('lightstats', [
            'name' => 'Создание тестового светостата',
        ]);
    }

    public function test_post_lightstat_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateLightstat();

        $response = $this->actingAs($user)->post('lightstats/'.$data['lightstat']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового светостата',
            'placetype' => 'port',
            'id_object' => $data['lightstat']->id_object,
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

        $response->assertRedirect('/lightstats/'.$data['lightstat']->id.'/edit');

        $this->assertDatabaseHas('lightstats', [
            'name' => 'Обновление тестового светостата',
        ]);
    }

    public function test_delete_lightstat_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateLightstat();

        $response = $this->actingAs($user)
            ->postJson('lightstats/delete', ['id' => $data['lightstat']->id], ['X-Requested-With' => 'XMLHttpRequest']);

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

        $generator = new SensorsData();

        $data = $generator->generateMotionsensor();

        $response = $this->actingAs($user)->get('/motionsensors/'.$data['motionsensor']->id.'/edit');

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

        $response->assertRedirect('/motionsensors/'.($motionsensor ? $motionsensor->id : 1).'/edit');

        $this->assertDatabaseHas('motionsensors', [
            'name' => 'Создание тестового Датчика движения',
        ]);
    }

    public function test_post_motionsensor_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateMotionsensor();

        $response = $this->actingAs($user)->post('motionsensors/'.$data['motionsensor']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового Датчика движения',
            'id_object' => $data['motionsensor']->id_object,
            'port_id' => null,
        ]);

        $response->assertRedirect('/motionsensors/'.$data['motionsensor']->id.'/edit');

        $this->assertDatabaseHas('motionsensors', [
            'name' => 'Обновление тестового Датчика движения',
        ]);
    }

    public function test_delete_motionsensor_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateMotionsensor();

        $response = $this->actingAs($user)
            ->postJson('motionsensors/delete', ['id' => $data['motionsensor']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('motionsensors', [
            'name' => 'Тестовый Датчик движения',
        ]);
    }

    public function test_get_usensors_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/usensors');

        $response->assertStatus(200);
    }

    public function test_get_usensor_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/usensors/create');

        $response->assertStatus(200);
    }

    public function test_get_usensor_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateUsensor();

        $response = $this->actingAs($user)->get('/usensors/'.$data['usensor']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_usensor_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateUsensor();

        $response = $this->actingAs($user)->post('/usensors', [
            'name' => 'Создание тестового универсального датчика',
            'type' => 'htu21d',
            'device_id' => $data['device']->id,
            'port_SCL' => $data['port']->id,
            'port_SDA' => $data['port']->id,
            'room' => null,
        ]);

        $response->assertRedirect('/usensors');

        $this->assertDatabaseHas('usensors', [
            'name' => 'Создание тестового универсального датчика',
        ]);
    }

    public function test_post_usensor_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateUsensor();

        $response = $this->actingAs($user)->post('usensors/'.$data['usensor']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового универсального датчика',
            'id_object' => $data['usensor']->id_object,
            'device_id' => $data['usensor']->device_id,
            'port_SCL' => $data['usensor']->port_SCL,
            'port_SDA' => $data['usensor']->port_SDA,
        ]);

        $response->assertRedirect('/usensors/'.$data['usensor']->id.'/edit');

        $this->assertDatabaseHas('usensors', [
            'name' => 'Обновление тестового универсального датчика',
        ]);
    }

    public function test_delete_usensor_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateUsensor();

        $response = $this->actingAs($user)
            ->postJson('usensors/delete', ['id' => $data['usensor']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('usensors', [
            'name' => 'Тестовый универсальный датчик',
        ]);
    }

    public function test_get_drycontacts_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/drycontacts');

        $response->assertStatus(200);
    }

    public function test_get_drycontact_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/drycontacts/create');

        $response->assertStatus(200);
    }

    public function test_get_drycontact_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateDrycontact();

        $response = $this->actingAs($user)->get('/drycontacts/'.$data['drycontact']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_drycontact_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/drycontacts', [
            'name' => 'Создание тестового сухого контакта',
            'device_id' => null,
            'method_on' => null,
            'method_off' => null,
            'param_method_on' => null,
            'param_method_off' => null,
            'port_id' => null,
        ]);

        $drycontact = Drycontact::where('name', 'Создание тестового сухого контакта')->first();

        $response->assertRedirect('/drycontacts/'.($drycontact ? $drycontact->id : 1).'/edit');

        $this->assertDatabaseHas('drycontacts', [
            'name' => 'Создание тестового сухого контакта',
        ]);
    }

    public function test_post_drycontact_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateDrycontact();

        $response = $this->actingAs($user)->post('drycontacts/'.$data['drycontact']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового сухого контакта',
            'id_object' => $data['drycontact']->id_object,
            'device_id' => null,
            'method_on' => null,
            'method_off' => null,
            'param_method_on' => null,
            'param_method_off' => null,
            'port_id' => null,
        ]);

        $response->assertRedirect('/drycontacts/'.$data['drycontact']->id.'/edit');

        $this->assertDatabaseHas('drycontacts', [
            'name' => 'Обновление тестового сухого контакта',
        ]);
    }

    public function test_delete_drycontact_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateDrycontact();

        $response = $this->actingAs($user)
            ->postJson('drycontacts/delete', ['id' => $data['drycontact']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('drycontacts', [
            'name' => 'Тестовый сухой контакт',
        ]);
    }

    public function test_get_carbmonoxide_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/carbmonoxide');

        $response->assertStatus(200);
    }

    public function test_get_carbmonoxide_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/carbmonoxide/create');

        $response->assertStatus(200);
    }

    public function test_get_carbmonoxide_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateCarbmonoxide();

        $response = $this->actingAs($user)->get('/carbmonoxide/'.$data['carbmonoxide']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_carbmonoxide_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/carbmonoxide', [
            'name' => 'Создание тестового датчика УГ',
            'device_id' => null,
            'cur_value' => 0,
            'low_value' => 50,
            'high_value' => 100,
            'calibration' => 2,
        ]);

        $carbmonoxide = Carbmonoxide::where('name', 'Создание тестового датчика УГ')->first();

        $response->assertRedirect('/carbmonoxide/'.($carbmonoxide ? $carbmonoxide->id : 1).'/edit');

        $this->assertDatabaseHas('carbmonoxide', [
            'name' => 'Создание тестового датчика УГ',
        ]);
    }

    public function test_post_carbmonoxide_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateCarbmonoxide();

        $response = $this->actingAs($user)->post('carbmonoxide/'.$data['carbmonoxide']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового датчика УГ',
            'id_object' => $data['carbmonoxide']->id_object,
            'port' => null,
            'cur_value' => 0,
            'low_value' => 50,
            'high_value' => 100,
            'calibration' => 2,
        ]);

        $response->assertRedirect('/carbmonoxide/'.$data['carbmonoxide']->id.'/edit');

        $this->assertDatabaseHas('carbmonoxide', [
            'name' => 'Обновление тестового датчика УГ',
        ]);
    }

    public function test_delete_carbmonoxide_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SensorsData();

        $data = $generator->generateCarbmonoxide();

        $response = $this->actingAs($user)
            ->postJson('carbmonoxide/delete', ['id' => $data['carbmonoxide']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('carbmonoxide', [
            'name' => 'Тестовый датчик УГ',
        ]);
    }
}
