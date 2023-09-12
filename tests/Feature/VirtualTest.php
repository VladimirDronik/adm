<?php

namespace Tests\Feature;

use App\Models\Virtual;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\VirtualsData;

class VirtualTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_virtuals_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/virtuals');

        $response->assertStatus(200);
    }

    public function test_get_virtual_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/virtuals/create');

        $response->assertStatus(200);
    }

    public function test_get_virtual_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new VirtualsData();

        $data = $generator->generateVirtual();

        $response = $this->actingAs($user)->get('/virtuals/'. $data['virtual']->id .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_virtual_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/virtuals', [
            'name' => 'Создание тестового виртуального устройства',
        ]);

        $virtual = Virtual::where('name', 'Создание тестового виртуального устройства')->first();

        $response->assertRedirect('/virtuals/'. ($virtual ? $virtual->id : 1) .'/edit');

        $this->assertDatabaseHas('virtualsdev', [
            'name' => 'Создание тестового виртуального устройства',
        ]);
    }

    public function test_post_virtual_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new VirtualsData();

        $data = $generator->generateVirtual();

        $response = $this->actingAs($user)->post('virtuals/'. $data['virtual']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового виртуального устройства',
            'id_object' => $data['object']->id,
            'alice_command' => null,
            'room' => 0,
            'event_idobject' => null,
        ]);

        $response->assertRedirect('/virtuals/'. $data['virtual']->id .'/edit');

        $this->assertDatabaseHas('virtualsdev', [
            'name' => 'Обновление тестового виртуального устройства',
        ]);
    }

    public function test_delete_virtual_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new VirtualsData();

        $data = $generator->generateVirtual();

        $response = $this->actingAs($user)
            ->postJson('virtuals/delete', ['id' => $data['virtual']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('virtualsdev', [
            'name' => 'Тестовое виртуальное устройство',
        ]);
    }
}
