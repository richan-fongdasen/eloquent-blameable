<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/../config/blameable.php') => config_path('blameable.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/blameable.php'), 'blameable');

        $this->app->singleton(BlameableObserver::class, function () {
            return new BlameableObserver();
        });

        $this->app->singleton(BlameableService::class, function () {
            return new BlameableService();
        });
    }
}
