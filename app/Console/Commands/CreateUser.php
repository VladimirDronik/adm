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
    protected $signature = 'create:user {role} {login} {password}';

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
        $userTypes = User::getTypes();
        $type = $this->argument('role');

        if (!in_array(trim($type), $userTypes, true)) {
            $this->error("User role is not valid");
            return;
        }

        $login = $this->argument('login');

        if (User::where('login', $login)->exists()) {
            $this->error("Login already exists");
            return;
        }

        $password = $this->argument('password');

        if (strlen($password) < 6) {
            $this->error("Password contains less then six symbols");
            return;
        }

        $user = new User();
        $user->type = $type;
        $user->login = $login;
        $user->password = bcrypt($password);
        $user->save();

        $this->info("User created");
    }
}
