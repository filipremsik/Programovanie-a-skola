<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

        // Gate => Permission | Simple Role
        Gate::define('admin', function (User $user) {
            return (bool) $user->admin;
        });

        // Permission 
        Gate::define('create-product', function (User $user) {
            return (bool) $user->admin;
        });

        Gate::define('temporary-login', function (User $user) {
            return (bool) $user->temporary;
        });

        Gate::define('temporary-profile', function (User $user) {
            if ($user->temporary == true) {
                return (bool)false;
            } else {
                return (bool) true;
            }
        });
    }
}