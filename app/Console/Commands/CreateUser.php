<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $login = $this->ask('input user login');

        if (User::where('login', $login)->exists()) {
            $this->error("login already exists");
            return;
        }

        $password = $this->ask('input user password (six or more symbols)');

        if (strlen($password) < 6) {
            $this->error("password contains less then six symbols");
            return;
        }

        $user = new User();
        $user->login = $login;
        $user->password = bcrypt($password);
        $user->save();

        $this->info("User created");
    }
}
