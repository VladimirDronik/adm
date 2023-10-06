<?php

namespace Tests\Feature;

use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\RoomsData;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_rooms_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/rooms');

        $response->assertStatus(200);
    }

    public function test_get_rooms_group_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new RoomsData();

        $data = $generator->generateRoom();

        $response = $this->actingAs($user)->get('/rooms/group/'.$data['room_group']->id);

        $response->assertStatus(200);
    }

    public function test_get_room_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new RoomsData();

        $data = $generator->generateRoom();

        $response = $this->actingAs($user)->get('/rooms/'.$data['room']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_room_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->postJson('/rooms/store', [
            'name' => 'Создание тестовой комнаты',
            'image' => 'noimage.png',
            'style' => null,
            'group_id' => 0,
            'type' => 'room',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseHas('rooms', [
            'name' => 'Создание тестовой комнаты',
        ]);
    }

    public function test_post_room_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new RoomsData();

        $data = $generator->generateRoom();

        $response = $this->actingAs($user)->post('rooms/'.$data['room']->id, [
            '_method' => 'PUT',
            'group_room' => $data['room_group']->id,
            'temperature_normal' => 15,
            'temperature_night' => 15,
            'temperature_eco' => 15,
        ]);

        $response->assertRedirect('/rooms/'.$data['room']->id.'/edit');

        $this->assertDatabaseHas('rooms', [
            'name' => 'Тестовая комната',
        ]);
    }

    public function test_delete_room_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new RoomsData();

        $data = $generator->generateRoom();

        $response = $this->actingAs($user)
            ->postJson('rooms/delete', ['id' => $data['room']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('rooms', [
            'name' => 'Тестовое виртуальное устройство',
        ]);
    }
}
