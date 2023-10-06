<?php

namespace App\Providers;

use App\Models\UserPermission;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->register();
    }

    public function register()
    {
        $slugs = UserPermission::slugs();

        foreach ($slugs as $slug) {
            Gate::define($slug, function (User $user) use ($slug) {
                return $user->hasAccess($slug);
            });
        }

        Gate::define('superadmin', function (User $user) {
            return $user->is_super_admin;
        });

        Gate::define('admin', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('user', function (User $user) {
            return $user->is_user;
        });
    }
}
