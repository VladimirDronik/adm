<?php

namespace Tests\Feature;

use App\Models\Conditioner;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\ConditionersData;

class ConditionerTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_conditioners_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/conditioners');

        $response->assertStatus(200);
    }

    public function test_get_conditioner_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/conditioners/create');

        $response->assertStatus(200);
    }

    public function test_get_conditioner_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ConditionersData();

        $data = $generator->generateConditioner();

        $response = $this->actingAs($user)->get('/conditioners/'.$data['conditioner']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_conditioner_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ConditionersData();

        $data = $generator->generateConditioner();

        $response = $this->actingAs($user)->post('/conditioners', [
            'vendor_id' => $data['conditioner_vendor']->id,
            'model_id' => $data['conditioner_model']->id,
            'device_id' => $data['device']->id,
            'wb_mir' => '123456789',
            'room_id' => $data['room']->id,
        ]);

        $conditioner = Conditioner::where('wb_mir', '123456789')->first();

        $response->assertRedirect('/conditioners/'.($conditioner ? $conditioner->id : 1).'/edit');

        $this->assertDatabaseHas('conditioners', [
            'wb_mir' => '123456789',
        ]);
    }

    public function test_post_conditioner_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ConditionersData();

        $data = $generator->generateConditioner();

        $response = $this->actingAs($user)->post('conditioners/'.$data['conditioner']->id, [
            '_method' => 'PUT',
            'id_object' => $data['object']->id,
            'device_id' => $data['device']->id,
            'id_room' => $data['room']->id,
            'wb_mir' => '123456789',
            'operationMode' => 'cool',
            'fanMode' => 'low',
            'temp' => '16',
        ]);

        $response->assertRedirect('/conditioners/'.$data['conditioner']->id.'/edit');

        $this->assertDatabaseHas('conditioners', [
            'wb_mir' => '123456789',
        ]);
    }

    public function test_delete_conditioner_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new ConditionersData();

        $data = $generator->generateConditioner();

        $response = $this->actingAs($user)
            ->postJson('conditioners/delete', ['id' => $data['conditioner']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('conditioners', [
            'wb_mir' => '111111',
        ]);
    }
}
