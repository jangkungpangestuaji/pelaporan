<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Gate::define('admin', function (User $user) {
            return $user->level === 'admin';
        });
        Gate::define('staff', function (User $user) {
            return $user->level === 'staff';
        });
        Gate::define('mitra', function (User $user) {
            return $user->level === 'mitra';
        });
    }
}
