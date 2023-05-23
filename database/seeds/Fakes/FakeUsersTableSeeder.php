<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;

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
            'password' => bcrypt(self::PASSWORD),
            'created_at' => $this->now,
            'updated_at' => $this->now
        ];
    }

    public function getUser()
    {
        return [
            'login' => 'user',
            'type' => User::TYPE_USER,
            'password' => bcrypt(self::PASSWORD),
            'created_at' => $this->now,
            'updated_at' => $this->now
        ];
    }

    public function insertAdmin()
    {
        try {
            DB::table('users')->insert($this->getAdmin());
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
        $this->insertAdmin();
        $this->insertUser();
    }
}
