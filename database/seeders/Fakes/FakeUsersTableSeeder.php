<?php

namespace Database\Seeders\Fakes;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FakeUsersTableSeeder extends Seeder
{
    const PASSWORD = '111111';

    private $now;

    public function __construct()
    {
        $this->now = date('Y-m-d H:i:s');
    }

    public function getAdmin()
    {
        return [
            'login' => 'admin',
            'type' => User::TYPE_ADMIN,
            'password' => Hash::make(self::PASSWORD),
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ];
    }

    public function getSuperAdmin()
    {
        return [
            'login' => 'superadmin',
            'type' => User::TYPE_SUPERADMIN,
            'password' => Hash::make(self::PASSWORD),
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ];
    }

    public function getUser()
    {
        return [
            'login' => 'user',
            'type' => User::TYPE_USER,
            'password' => Hash::make(self::PASSWORD),
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ];
    }

    public function insertAdmin()
    {
        try {
            DB::table('users')->insert($this->getAdmin());
        } catch (\Throwable $e) {

        }
    }

    public function insertSuperAdmin()
    {
        try {
            DB::table('users')->insert($this->getSuperAdmin());
        } catch (\Throwable $e) {

        }
    }

    public function insertUser()
    {
        try {
            DB::table('users')->insert($this->getUser());
        } catch (\Throwable $e) {

        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertSuperAdmin();
        $this->insertAdmin();
        $this->insertUser();
    }
}
