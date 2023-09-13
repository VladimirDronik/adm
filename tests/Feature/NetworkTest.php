<?php

namespace Tests\Feature;

use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NetworkTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_network_edit_page_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/network');

        $response->assertStatus(200);
    }

    public function test_post_network_update_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->post('/network', [
            '_method' => 'PUT',
            'main_ip' => '192.168.1.50',
            'main_mask' => '255.255.255.0',
            'main_gateway' => '192.168.1.1',
            'ip' => '10.8.0.1',
            'mask' => '255.255.255.0',
        ]);

        $response->assertRedirect('/network')
            ->assertSessionHas('success', 'Данные успешно обновлены');
    }
}
