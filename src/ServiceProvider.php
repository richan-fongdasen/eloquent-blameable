<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Support\ServiceProvider as Provider;
use RichanFongdasen\EloquentBlameable\BlameableObserver;
use RichanFongdasen\EloquentBlameable\BlameableService;

class ServiceProvider extends Provider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BlameableObserver::class, function () {
            return new BlameableObserver();
        });

        $this->app->singleton(BlameableService::class, function () {
            return new BlameableService();
        });
    }
}
