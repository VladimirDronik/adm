<?php

namespace Tests\Feature;

use App\Models\SchedulerTask;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\SchedulersData;

class SchedulerTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_scheduler_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/scheduler');

        $response->assertStatus(200);
    }

    public function test_get_scheduler_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/scheduler/create');

        $response->assertStatus(200);
    }

    public function test_get_scheduler_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SchedulersData();

        $data = $generator->generateScheduler();

        $response = $this->actingAs($user)->get('/scheduler/'. $data['scheduler']->id .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_scheduler_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SchedulersData();

        $data = $generator->generateScheduler();

        $response = $this->actingAs($user)->post('/scheduler', [
            'name' => 'Создание тестовой задачи',
            'active' => 1,
            'type' => 'script',
            'object' => null,
            'method' => null,
            'method_params' => null,
            'script' => $data['script']->id,
        ]);

        $scheduler = SchedulerTask::where('name', 'Создание тестовой задачи')->first();

        $response->assertRedirect('/scheduler/'. ($scheduler ? $scheduler->id : 1) .'/edit');

        $this->assertDatabaseHas('scheduler_tasks', [
            'name' => 'Создание тестовой задачи',
        ]);
    }

    public function test_post_scheduler_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SchedulersData();

        $data = $generator->generateScheduler();

        $response = $this->actingAs($user)->post('scheduler/'. $data['scheduler']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестовой задачи',
            'active' => 1,
            'type' => 'script',
            'object' => null,
            'method' => null,
            'method_params' => null,
            'script' => $data['script']->id,
        ]);

        $response->assertRedirect('/scheduler/'. $data['scheduler']->id .'/edit');

        $this->assertDatabaseHas('scheduler_tasks', [
            'name' => 'Обновление тестовой задачи',
        ]);
    }

    public function test_delete_scheduler_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new SchedulersData();

        $data = $generator->generateScheduler();

        $response = $this->actingAs($user)
            ->postJson('scheduler/delete', ['id' => $data['scheduler']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('scheduler_tasks', [
            'name' => 'Тестовая задача',
        ]);
    }
}
