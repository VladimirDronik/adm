<?php

namespace Tests\Feature;

use App\User;
use Database\Seeders\Tests\TestAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraphTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = TestAdminSeeder::class;

    public function test_get_graphs_termostats_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/graphs/termostats');

        $response->assertStatus(200);
    }

    public function test_get_graphs_humidities_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/graphs/humidities');

        $response->assertStatus(200);
    }

    public function test_get_graphs_lights_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/graphs/lights');

        $response->assertStatus(200);
    }

    public function test_get_graphs_counts_list_successful(): void
    {
        $user = User::where('login', 'TestAdmin')->first();

        $response = $this->actingAs($user)->get('/graphs/counts');

        $response->assertStatus(200);
    }
}
