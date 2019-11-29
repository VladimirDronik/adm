<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'password' => bcrypt(self::PASSWORD),
            'created_at' => $this->now,
            'updated_at' => $this->now
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('users')->insert($this->getAdmin());
        } catch (\Throwable $e) {

        }
    }
}
