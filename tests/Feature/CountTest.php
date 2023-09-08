<?php

namespace Tests\Feature;

use App\Models\Count;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\CountsData;

class CountTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_counts_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/counts');

        $response->assertStatus(200);
    }

    public function test_get_count_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/counts/create');

        $response->assertStatus(200);
    }

    public function test_get_count_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CountsData();

        $data = $generator->generateCount();

        $response = $this->actingAs($user)->get('/counts/'. $data['count']->id .'/edit');

        $response->assertStatus(200);
    }

    public function test_post_count_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CountsData();

        $data = $generator->generateCount();

        $response = $this->actingAs($user)->post('/counts', [
            'name' => 'Создание тестового счетчика',
            'type' => Count::TYPE_ELECTRO,
            'device_id' => null,
            'port_id' => null,
            'unit' => 'КВт/ч',
            'impulse' => 1,
            'today_value' => 10,
            'total_value' => 20,
        ]);

        $count = Count::where('name', 'Создание тестового счетчика')->first();

        $response->assertRedirect('/counts/'. ($count ? $count->id : 1) .'/edit');

        $this->assertDatabaseHas('counts', [
            'name' => 'Создание тестового счетчика',
        ]);
    }

    public function test_post_count_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CountsData();

        $data = $generator->generateCount();

        $response = $this->actingAs($user)->post('counts/'. $data['count']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестового счетчика',
            'id_object' => $data['object']->id,
            'device_id' => null,
            'port_id' => null,
            'impulse' => 1,
            'today_value' => 10,
            'total_value' => 20,
            'event_idobject' => $data['object']->id,
        ]);

        $response->assertRedirect('/counts/'. $data['count']->id .'/edit');

        $this->assertDatabaseHas('counts', [
            'name' => 'Обновление тестового счетчика',
        ]);
    }

    public function test_delete_count_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CountsData();

        $data = $generator->generateCount();

        $response = $this->actingAs($user)
            ->postJson('counts/delete', ['id' => $data['count']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('counts', [
            'name' => 'Тестовый счетчик',
        ]);
    }
}
