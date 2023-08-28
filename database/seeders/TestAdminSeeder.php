<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'login' => 'TestAdmin',
            'type' => User::TYPE_SUPERADMIN,
            'password' => Hash::make(111111),
        ]);
    }
}
