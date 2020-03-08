<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Check if the application runs with Lumen.
     *
     * @return bool
     */
    protected function isLumen(): bool
    {
        return strpos($this->app->version(), 'Lumen') !== false;
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (!$this->isLumen()) {
            $this->publishes([
                realpath(dirname(__DIR__).'/config/blameable.php') => config_path('blameable.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = realpath(dirname(__DIR__).'/config/blameable.php');

        if ($configPath !== false) {
            $this->mergeConfigFrom($configPath, 'blameable');
        }

        $this->app->singleton(BlameableObserver::class, function (): BlameableObserver {
            return new BlameableObserver();
        });

        $this->app->singleton(BlameableService::class, function (): BlameableService {
            return new BlameableService();
        });
    }
}
