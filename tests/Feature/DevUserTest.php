<?php

namespace Tests\Feature;

use App\Models\User as DevUser;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\DevUsersData;

class DevUserTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_dev_users_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_get_dev_user_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/users/create');

        $response->assertStatus(200);
    }

    public function test_get_dev_user_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DevUsersData();

        $data = $generator->generateDevUser();

        $response = $this->actingAs($user)->get('/users/'.$data['dev_user']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_dev_user_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/users', [
            'name' => 'Создание тестового пользователя',
            'dev_id' => 1,
            'telegram_id' => null,
            'push_id' => null,
            'phone_number' => null,
        ]);

        $devUser = DevUser::where('name', 'Создание тестового пользователя')->first();

        $response->assertRedirect('/users?'.$devUser->id);

        $this->assertDatabaseHas('devusers', [
            'name' => 'Создание тестового пользователя',
        ]);
    }

    public function test_post_dev_user_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DevUsersData();

        $data = $generator->generateDevUser();

        $response = $this->actingAs($user)->post('users/'.$data['dev_user']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового пользователя',
            'dev_id' => 1,
            'telegram_id' => null,
            'push_id' => null,
            'phone_number' => null,
            'telegram_send' => 0,
            'push_send' => 0,
            'sms_send' => 0,
        ]);

        $response->assertRedirect('/users');

        $this->assertDatabaseHas('devusers', [
            'name' => 'Обновление тестового пользователя',
        ]);
    }

    public function test_delete_dev_user_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new DevUsersData();

        $data = $generator->generateDevUser();

        $response = $this->actingAs($user)
            ->postJson('users/delete', ['id' => $data['dev_user']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('devusers', [
            'name' => 'Тестовый пользователь',
        ]);
    }
}
