<?php

namespace Tests\Feature;

use App\Models\Curtain;
use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestData\CurtainsData;

class CurtainTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_curtains_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/curtains');

        $response->assertStatus(200);
    }

    public function test_get_curtain_create_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/curtains/create');

        $response->assertStatus(200);
    }

    public function test_get_curtain_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CurtainsData();

        $data = $generator->generateCurtain();

        $response = $this->actingAs($user)->get('/curtains/'.$data['curtain']->id.'/edit');

        $response->assertStatus(200);
    }

    public function test_post_curtain_store_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CurtainsData();

        $data = $generator->generateCurtain();

        $response = $this->actingAs($user)->post('/curtains', [
            'name' => 'Создание тестовой шторы',
            'type' => Curtain::TYPE_CURTAIN,
            'place' => Curtain::PLACE_RS485,
            'device_id' => $data['device']->id,
            'address' => '100',
            'group' => '100',
        ]);

        $curtain = Curtain::where('name', 'Создание тестовой шторы')->first();

        $response->assertRedirect('/curtains/'.($curtain ? $curtain->id : 1).'/edit');

        $this->assertDatabaseHas('curtains', [
            'name' => 'Создание тестовой шторы',
        ]);
    }

    public function test_post_curtain_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CurtainsData();

        $data = $generator->generateCurtain();

        $response = $this->actingAs($user)->post('curtains/'.$data['curtain']->id, [
            '_method' => 'PUT',
            'name' => 'Обновление тестовой шторы',
            'type' => Curtain::TYPE_CURTAIN,
            'place' => Curtain::PLACE_RS485,
            'id_object' => $data['object']->id,
            'device_id' => $data['device']->id,
            'address' => 100,
            'group' => 100,
            'alice_command' => null,
            'room' => 0,
            'event_idobject' => $data['object']->id,
        ]);

        $response->assertRedirect('/curtains/'.$data['curtain']->id.'/edit');

        $this->assertDatabaseHas('curtains', [
            'name' => 'Обновление тестовой шторы',
        ]);
    }

    public function test_delete_curtain_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $generator = new CurtainsData();

        $data = $generator->generateCurtain();

        $response = $this->actingAs($user)
            ->postJson('curtains/delete', ['id' => $data['curtain']->id], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('curtains', [
            'name' => 'Тестовая штора',
        ]);
    }
}
