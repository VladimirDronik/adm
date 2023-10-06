<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\SettingsData;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_settings_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(200);
    }

    public function test_get_setting_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/settings/create');

        $response->assertStatus(200);
    }

    public function test_get_time_zone_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/time_zone/create');

        $response->assertStatus(200);
    }

    public function test_get_setting_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SettingsData();

        $data = $generator->generateSetting();

        $response = $this->actingAs($user)->get('/settings/'.$data['setting']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_get_time_zone_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SettingsData();

        $data = $generator->generateSetting();

        $response = $this->actingAs($user)->get('/time_zone/'.$data['time_zone']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_setting_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/settings', [
            'name' => 'test_setting',
            'value' => 'test_value',
            'comment' => 'Тестовая настройка',
        ]);

        $setting = Setting::where('name', 'test_setting')->first();

        $response->assertRedirect('/settings/'.($setting ? $setting->id : 1).'/edit');

        $this->assertDatabaseHas('settings', [
            'name' => 'test_setting',
        ]);
    }

    public function test_post_time_zone_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/time_zone', [
            'name' => 'time_zone',
            'value' => 'Europe/Moscow',
            'comment' => 'Тестовая настройка часового пояса',
        ]);

        $setting = Setting::where('name', 'time_zone')->first();

        $response->assertRedirect('/time_zone/'.($setting ? $setting->id : 1).'/edit');

        $this->assertDatabaseHas('settings', [
            'name' => 'time_zone',
        ]);
    }

    public function test_post_setting_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SettingsData();

        $data = $generator->generateSetting();

        $response = $this->actingAs($user)->post('settings/'.$data['setting']->id, [
            '_method' => 'PUT',
            'name' => 'upd_test_setting',
            'value' => 'upd_test_value',
            'comment' => 'Тестовая настройка',
        ]);

        $response->assertRedirect('/settings/'.$data['setting']->id.'/edit');

        $this->assertDatabaseHas('settings', [
            'name' => 'upd_test_setting',
        ]);
    }

    public function test_post_time_zone_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SettingsData();

        $data = $generator->generateSetting();

        $response = $this->actingAs($user)->post('time_zone/'.$data['time_zone']->id, [
            '_method' => 'PUT',
            'name' => 'time_zone',
            'value' => 'Europe/Moscow',
            'comment' => 'Обновление тестовой настройки часового пояса',
        ]);

        $response->assertRedirect('/time_zone/'.$data['time_zone']->id.'/edit');

        $this->assertDatabaseHas('settings', [
            'comment' => 'Обновление тестовой настройки часового пояса',
        ]);
    }

    public function test_delete_setting_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SettingsData();

        $data = $generator->generateSetting();

        $response = $this->actingAs($user)
            ->postJson('settings/delete', ['id' => $data['setting']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('settings', [
            'name' => 'test_setting',
        ]);
    }
}
