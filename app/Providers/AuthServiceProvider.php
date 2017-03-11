<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;
use App\Role;
use App\Placemark;
use Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        App\Placemark::class => App\Policies\Placemark::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function(User $user) {
            return $user->role_id === Role::ROLE_ADMIN;
        });

        Gate::define('moder', function(User $user, Placemark $placemark = null) {
            return ($user->role_id === Role::ROLE_ADMIN || $user->role_id === Role::ROLE_MODER) ||
               ($placemark && $placemark->user_id === $user->id) ||
               Auth::check();
        });

        /*Gate::define('placemark-edit', function(App\User $user, App\Placemark $placemark) {
            return $user->id == $placemark->user_id;
        });*/
    }
}
