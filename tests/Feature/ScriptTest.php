<?php

namespace Tests\Feature;

use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\ScriptsData;

class ScriptTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_scripts_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/scripts');

        $response->assertStatus(200);
    }

    public function test_get_script_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/scripts/create');

        $response->assertStatus(200);
    }

    public function test_get_script_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ScriptsData();

        $data = $generator->generateScript();

        $response = $this->actingAs($user)->get('/scripts/'.$data['script']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_script_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/scripts', [
            'name' => 'Создание тестового скрипта',
            'code' => 'create_test_script',
        ]);

        $response->assertRedirect('/scripts');

        $this->assertDatabaseHas('scripts', [
            'name' => 'Создание тестового скрипта',
        ]);
    }

    public function test_post_script_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ScriptsData();

        $data = $generator->generateScript();

        $response = $this->actingAs($user)->post('scripts/'.$data['script']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового скрипта',
            'code' => 'update_test_script',
        ]);

        $response->assertRedirect('/scripts/'.$data['script']->id.'/edit');

        $this->assertDatabaseHas('scripts', [
            'name' => 'Обновление тестового скрипта',
        ]);
    }

    public function test_delete_script_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ScriptsData();

        $data = $generator->generateScript();

        $response = $this->actingAs($user)
            ->postJson('scripts/delete', ['id' => $data['script']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('scripts', [
            'name' => 'Тестовый скрипт',
        ]);
    }
}
