<?php

namespace Tests\Feature;

use App\Models\View;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\ViewsData;

class ViewTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_views_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/views');

        $response->assertStatus(200);
    }

    public function test_get_view_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/views/create');

        $response->assertStatus(200);
    }

    public function test_get_view_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ViewsData();

        $data = $generator->generateView();

        $response = $this->actingAs($user)->get('/views/'.$data['view']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_view_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/views', [
            'type' => 'dimmer',
            'description' => 'Создание тестового отображения',
            'active' => '1',
            'safe_type' => null,
            'id_object' => null,
            'id_method' => null,
            'link' => null,
            'on_method_params' => null,
            'off_method' => null,
            'off_method_params' => null,
            'title' => null,
            'color' => 'null',
            'icon_image' => 'ela/images/rooms/noimage.png',
            'room' => 0,
            'scene' => null,
            'position_left' => 0,
            'position_top' => 0,
            'enabletermostat' => true,
            'lowval_termostat' => 10,
            'highval_termostat' => 26,
            'pushlabel' => false,
            'modallabel' => false,
            'label_longclick_text' => null,
        ]);

        $view = View::where('description', 'Создание тестового отображения')->first();

        $response->assertRedirect('/views/'.($view ? $view->id : 1).'/edit');

        $this->assertDatabaseHas('view_items', [
            'description' => 'Создание тестового отображения',
        ]);
    }

    public function test_post_view_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ViewsData();

        $data = $generator->generateView();

        $response = $this->actingAs($user)->post('views/'.$data['view']->id, [
            '_method' => 'PUT',
            'description' => 'Обновление тестового отображения',
            'type' => 'dimmer',
            'active' => 1,
            'safe_type' => 'confirm',
            'id_object' => null,
            'id_method' => null,
            'link' => null,
            'on_method_params' => null,
            'off_method' => null,
            'off_method_params' => null,
            'title' => null,
            'color' => null,
            'icon_image' => 'ela/images/rooms/noimage.png',
            'room' => 0,
            'scene' => null,
            'position_left' => 0,
            'position_top' => 0,
            'lowval_termostat' => null,
            'highval_termostat' => null,
            'label_longclick_text' => null,
        ]);

        $response->assertRedirect('/views/'.$data['view']->id.'/edit');

        $this->assertDatabaseHas('view_items', [
            'description' => 'Обновление тестового отображения',
        ]);
    }

    public function test_delete_view_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ViewsData();

        $data = $generator->generateView();

        $response = $this->actingAs($user)
            ->postJson('views/delete', ['id' => $data['view']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('view_items', [
            'description' => 'Тестовое отображение',
        ]);
    }
}
