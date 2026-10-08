<?php

namespace App\Providers;

use App\Models\NavMenu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\CkanService::class);
    }

    public function boot(): void
    {
        // Share dynamic nav menus with all frontend views
        View::composer(['layouts.app'], function ($view) {
            $view->with('navMenus', NavMenu::with(['children', 'page'])->topLevel()->get());
        });
    }
}
