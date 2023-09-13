<?php

namespace Tests\Feature;

use App\Models\Scene;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\ScenesData;

class SceneTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_scenes_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/scenes');

        $response->assertStatus(200);
    }

    public function test_get_scene_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/scenes/create');

        $response->assertStatus(200);
    }

    public function test_get_scene_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ScenesData();

        $data = $generator->generateScene();

        $response = $this->actingAs($user)->get('/scenes/'. $data['scene']->id .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_scene_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/scenes', [
            'label' => 'Создание тестовой сцены',
            'active' => 1,
            '_image' => 'ela/images/scenes/IMG_6339.jpg',
            'background_color' => '#E9E9F0',
        ]);

        $scene = Scene::where('label', 'Создание тестовой сцены')->first();

        $response->assertRedirect('/scenes/'. ($scene ? $scene->id : 1) .'/edit');

        $this->assertDatabaseHas('scenes', [
            'label' => 'Создание тестовой сцены',
        ]);
    }

    public function test_post_scene_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ScenesData();

        $data = $generator->generateScene();

        $response = $this->actingAs($user)->post('scenes/'. $data['scene']->id, [
            '_method' => 'PUT',
            'label' => 'Обновление тестовой сцены',
            'active' => 1,
            '_image' => 'ela/images/scenes/IMG_6339.jpg',
            'background_color' => '#E9E9F0',
        ]);

        $response->assertRedirect('/scenes/'. $data['scene']->id .'/edit');

        $this->assertDatabaseHas('scenes', [
            'label' => 'Обновление тестовой сцены',
        ]);
    }

    public function test_delete_scene_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ScenesData();

        $data = $generator->generateScene();

        $response = $this->actingAs($user)
            ->postJson('scenes/delete', ['id' => $data['scene']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('scenes', [
            'label' => 'Тестовая сцена',
        ]);
    }
}
