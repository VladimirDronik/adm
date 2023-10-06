<?php

namespace Tests\Feature;

use App\Models\Lock;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\LocksData;

class LockTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_locks_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/locks');

        $response->assertStatus(200);
    }

    public function test_get_lock_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/locks/create');

        $response->assertStatus(200);
    }

    public function test_get_lock_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new LocksData();

        $data = $generator->generateLock();

        $response = $this->actingAs($user)->get('/locks/'.$data['lock']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_lock_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new LocksData();

        $data = $generator->generateLock();

        $response = $this->actingAs($user)->post('/locks', [
            'name' => 'Создание тестового замка',
            'type' => Lock::TYPE_LATCH,
            'device_id' => $data['device']->id,
            'port_id_open' => $data['port']->id,
            'port_id_close' => null,
            'hitepro_device_open' => null,
            'hitepro_device_close' => null,
            'place' => 'port',
            'time' => null,
        ]);

        $lock = Lock::where('name', 'Создание тестового замка')->first();

        $response->assertRedirect('/locks/'.($lock ? $lock->id : 1).'/edit');

        $this->assertDatabaseHas('locks', [
            'name' => 'Создание тестового замка',
        ]);
    }

    public function test_post_lock_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new LocksData();

        $data = $generator->generateLock();

        $response = $this->actingAs($user)->post('locks/'.$data['lock']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового замка',
            'id_object' => $data['object']->id,
            'device_id' => $data['device']->id,
            'port_id_open' => $data['port']->id,
            'port_id_close' => null,
            'hitepro_device_open' => null,
            'hitepro_device_close' => null,
            'time' => null,
            'place' => 'port',
            'alice_command' => null,
            'room' => 0,
            'event_idobject' => $data['object']->id,
        ]);

        $response->assertRedirect('/locks/'.$data['lock']->id.'/edit');

        $this->assertDatabaseHas('locks', [
            'name' => 'Обновление тестового замка',
        ]);
    }

    public function test_delete_lock_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new LocksData();

        $data = $generator->generateLock();

        $response = $this->actingAs($user)
            ->postJson('locks/delete', ['id' => $data['lock']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('locks', [
            'name' => 'Тестовый замок',
        ]);
    }
}
