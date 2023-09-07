<?php

namespace Tests\Feature;

use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\EngineeringData;

class EngineeringTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_engineering_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/engineering');

        $response->assertStatus(200);
    }

    public function test_get_boiler_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/boiler/create');

        $response->assertStatus(200);
    }

    public function test_get_boiler_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new EngineeringData();

        $data = $generator->generateBoiler();

        $response = $this->actingAs($user)->get('/boiler/'. $data['boiler']->id_object .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_boiler_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/boiler', [
            'name' => 'Создание тестового котла',
            'type_boiler' => 'ebus',
            'ip_address_boiler' => '161.165.20.179',
            'id_outside_thermostat' => null,
        ]);

        $response->assertRedirect('/engineering');

        $this->assertDatabaseHas('boiler', [
            'name' => 'Создание тестового котла',
        ]);
    }

    public function test_post_boiler_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new EngineeringData();

        $data = $generator->generateBoiler();

        $response = $this->actingAs($user)->post('boiler/'. $data['boiler']->id_object, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового котла',
            'ip_address' => '161.165.20.179',
            'id_outside_thermostat' => null,
            'thermostat' => '0',
            'boiler' => '1',
            'target_water_temp' => '45',
            'mode' => 'manual',
            'set_value' => '55',
        ]);

        $response->assertRedirect('/boiler/'. $data['boiler']->id_object .'/edit');

        $this->assertDatabaseHas('boiler', [
            'name' => 'Обновление тестового котла',
        ]);
    }

    public function test_delete_boiler_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new EngineeringData();

        $data = $generator->generateBoiler();

        $response = $this->actingAs($user)
            ->postJson(
                '/engineering/delete',
                ['id' => $data['boiler']->id_object, 'del_checkbox' => 0],
                ['X-Requested-With' => 'XMLHttpRequest']
            );

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('boiler', [
            'name' => 'Тестовый котел',
        ]);
    }

    public function test_get_boiler_gvs_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/boiler_gvs/create');

        $response->assertStatus(200);
    }

    public function test_get_boiler_gvs_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new EngineeringData();

        $data = $generator->generateBoilerGvs();

        $response = $this->actingAs($user)->get('/boiler_gvs/'. $data['boiler_gvs']->id_object .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_boiler_gvs_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/boiler_gvs', [
            'name' => 'Создание тестового котла ГВС',
            'type_boiler' => 'proterm',
            'ip_address_boiler' => '161.165.20.179',
        ]);

        $response->assertRedirect('/engineering');

        $this->assertDatabaseHas('boiler_gvs', [
            'name' => 'Создание тестового котла ГВС',
        ]);
    }

    public function test_post_boiler_gvs_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new EngineeringData();

        $data = $generator->generateBoilerGvs();

        $response = $this->actingAs($user)->post('boiler_gvs/'. $data['boiler_gvs']->id_object, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового котла ГВС',
            'ip_address' => '161.165.20.179',
        ]);

        $response->assertRedirect('/boiler_gvs/'. $data['boiler_gvs']->id_object .'/edit');

        $this->assertDatabaseHas('boiler_gvs', [
            'name' => 'Обновление тестового котла ГВС',
        ]);
    }

    public function test_delete_boiler_gvs_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new EngineeringData();

        $data = $generator->generateBoilerGvs();

        $response = $this->actingAs($user)
            ->postJson(
                '/engineering/delete',
                ['id' => $data['boiler_gvs']->id_object, 'del_checkbox' => 0],
                ['X-Requested-With' => 'XMLHttpRequest']
            );

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('boiler', [
            'name' => 'Тестовый котел ГВС',
        ]);
    }
}
