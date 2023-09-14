<?php

namespace Tests\Feature;

use App\Models\NotificationSettings;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_notifications_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertStatus(200);
    }

    public function test_get_notification_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $notification = NotificationSettings::create([
            'name' => 'Оповещения о недоступности устройства',
            'type' => 'device_not_available',
            'priority' => 1,
            'message' => 'Устройство {$device->name} ({$device->ip}) недоступно'
        ]);

        $response = $this->actingAs($user)->get('/notifications/'. $notification->id .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_notification_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $notification = NotificationSettings::create([
            'name' => 'Оповещения о недоступности устройства',
            'type' => 'device_not_available',
            'priority' => 1,
            'message' => 'Устройство {$device->name} ({$device->ip}) недоступно'
        ]);

        $response = $this->actingAs($user)->post('notifications/'. $notification->id, [
            '_method' => 'PUT',
            'priority' => 1,
            'message' => 'Тестовое сообщение',
            'text_flag' => 0,
            'sound_flag' => 0,
            'id_sound' => null,
        ]);

        $response->assertRedirect('/notifications/' . $notification->id . '/edit');

        $this->assertDatabaseHas('notifsettings', [
            'message' => 'Тестовое сообщение',
        ]);
    }
}
