<?php

namespace Tests\Feature;

use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\PagesData;

class PageTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_pages_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/pages');

        $response->assertStatus(200);
    }

    public function test_get_page_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new PagesData();

        $data = $generator->generatePage();

        $response = $this->actingAs($user)->get('/pages/'.$data['page']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_get_element_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new PagesData();

        $data = $generator->generatePage();

        $response = $this->actingAs($user)->get('/page/'.$data['page']->id.'/createElement');

        $response->assertStatus(200);
    }

    public function test_get_element_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new PagesData();

        $data = $generator->generatePage();

        $response = $this->actingAs($user)->get('/elements/'.$data['element']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_page_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->postJson('/page/store', [
            'name' => 'Создание тестовой страницы',
            'type' => '2field',
            'link' => 'test-page',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseHas('pages', [
            'name' => 'Создание тестовой страницы',
        ]);
    }

    public function test_post_element_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new PagesData();

        $data = $generator->generatePage();

        $response = $this->actingAs($user)->post('/elements', [
            'name' => 'Создание тестового элемента',
            'type' => 'label',
            'page' => $data['page']->id,
            'image' => null,
            'position' => 1,
            'parent' => null,
            'value' => null,
            'id_object' => null,
            'method' => null,
            'handle' => null,
        ]);

        $response->assertRedirect('/pages/'.$data['page']->id.'/edit');

        $this->assertDatabaseHas('elements', [
            'name' => 'Создание тестового элемента',
        ]);
    }

    public function test_post_element_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new PagesData();

        $data = $generator->generatePage();

        $response = $this->actingAs($user)->post('elements/'.$data['element']->id, [
            '_method' => 'PUT',
            'type' => 'label',
            'name' => 'Обновление тестового элемента',
            'page' => $data['page']->id,
            'image' => null,
            'position' => 1,
            'parent' => null,
            'value' => null,
            'id_object' => null,
            'method' => null,
            'handle' => null,
        ]);

        $response->assertRedirect('/elements/'.$data['element']->id.'/edit');

        $this->assertDatabaseHas('elements', [
            'name' => 'Обновление тестового элемента',
        ]);
    }

    public function test_delete_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new PagesData();

        $data = $generator->generatePage();

        $response = $this->actingAs($user)
            ->postJson('page/delete', ['id' => $data['page']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('pages', [
            'name' => 'Тестовая страница',
        ]);
    }

    public function test_delete_element_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new PagesData();

        $data = $generator->generatePage();

        $response = $this->actingAs($user)
            ->postJson('element/delete', ['id' => $data['element']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('elements', [
            'name' => 'Тестовый элемент',
        ]);
    }
}
