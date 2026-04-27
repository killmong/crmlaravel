<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


public function boot()
{
    Blade::if('canManageUsers', function () {
        $user = Auth::user();
        return $user && $user->can('manage user profiles');
    });
Blade::if('isAdmin', fn() => Auth::user()?->hasRole('admin'));

    View::composer('*', function ($view) {
        $user = Auth::user();

        $menu = collect(config('menu'))->filter(function ($item) use ($user) {
            return $user && $user->can($item['permission']);
        });

        $view->with('menu', $menu);
    });
}

    }
