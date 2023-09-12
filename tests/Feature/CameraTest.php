<?php

namespace Tests\Feature;

use App\Models\Camera;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\CamerasData;

class CameraTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_cameras_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/cameras');

        $response->assertStatus(200);
    }

    public function test_get_camera_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/cameras/create');

        $response->assertStatus(200);
    }

    public function test_get_camera_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CamerasData();

        $data = $generator->generateCamera();

        $response = $this->actingAs($user)->get('/cameras/'. $data['camera']->id .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_camera_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CamerasData();

        $data = $generator->generateCamera();

        $response = $this->actingAs($user)->post('/cameras', [
            'name' => 'Создание тестовой камеры',
            'active' => 1,
            'link' => 'https://open.ivideon.com/embed/v2/?server=100-Oa52xERnVUudwQ9FBwhaa5&camera=0',
            'room' => $data['room']->id,
        ]);

        $camera = Camera::where('name', 'Создание тестовой камеры')->first();

        $response->assertRedirect('/cameras/'. ($camera ? $camera->id : 1) .'/edit');

        $this->assertDatabaseHas('cameras', [
            'name' => 'Создание тестовой камеры',
        ]);
    }

    public function test_post_camera_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CamerasData();

        $data = $generator->generateCamera();

        $response = $this->actingAs($user)->post('cameras/'. $data['camera']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестовой камеры',
            'active' => 1,
            'image' => 'https://openapi-alpha.ivideon.com/cameras/100-Oa52xERnVUudwQ9FBwhaa5:0/live_preview?op=GET&access_token=public',
            'link' => 'https://open.ivideon.com/embed/v2/?server=100-Oa52xERnVUudwQ9FBwhaa5&camera=0',
            'room' => $data['room']->id,
        ]);

        $response->assertRedirect('/cameras/'. $data['camera']->id .'/edit');

        $this->assertDatabaseHas('cameras', [
            'name' => 'Обновление тестовой камеры',
        ]);
    }

    public function test_delete_camera_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CamerasData();

        $data = $generator->generateCamera();

        $response = $this->actingAs($user)
            ->postJson('cameras/delete', ['id' => $data['camera']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('cameras', [
            'name' => 'Тестовое виртуальное устройство',
        ]);
    }
}
