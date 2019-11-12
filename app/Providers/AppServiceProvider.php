<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer(['threads.create', 'layouts.app'], function($view){
            $view->with('channels', Channel::all());
        });

        \Validator::extend('detectSpam', 'App\Rules\detectSpam@passes');
        Blade::component('components.SidebarLinks', 'SidebarLinks');
        Blade::component('components.guestAlert', 'GuestAlert');

    }

    /**
     * Register any application services.
     *app.b
     * @return void
     */
    public function register()
    {
        if($this->app->isLocal()){
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
