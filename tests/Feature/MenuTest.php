<?php

namespace Tests\Feature;

use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\MenuData;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_menu_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/menu');

        $response->assertStatus(200);
    }

    public function test_get_menu_group_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new MenuData();

        $data = $generator->generateMenu();

        $response = $this->actingAs($user)->get('/menu/group/'.$data['menu_group']->id);

        $response->assertStatus(200);
    }

    public function test_get_menu_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new MenuData();

        $data = $generator->generateMenu();

        $response = $this->actingAs($user)->get('/menu/'.$data['menu']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_menu_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->postJson('/menu/store', [
            'name' => 'Созд тестового меню',
            'image' => 'noimage.png',
            'style' => null,
            'type' => 'group',
            'link' => 'test-create-menu',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseHas('menu', [
            'name' => 'Созд тестового меню',
        ]);
    }

    public function test_post_menu_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new MenuData();

        $data = $generator->generateMenu();

        $response = $this->actingAs($user)->post('menu/'.$data['menu_group']->id, [
            '_method' => 'PUT',
            'title' => 'upd-test-menu-group',
            'link' => 'upd-test-menu-group',
            'parent' => 0,
        ]);

        $response->assertRedirect('/menu/'.$data['menu_group']->id.'/edit');

        $this->assertDatabaseHas('menu', [
            'title' => 'upd-test-menu-group',
            'link' => 'upd-test-menu-group',
        ]);
    }

    public function test_delete_menu_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new MenuData();

        $data = $generator->generateMenu();

        $response = $this->actingAs($user)
            ->postJson('menu/delete', ['id' => $data['menu']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('menu', [
            'name' => 'Тестовое меню',
        ]);
    }
}
