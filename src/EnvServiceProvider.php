<?php

namespace LaravelEnv\LaravelEnv;

use Illuminate\Support\ServiceProvider;
use LaravelEnv\LaravelEnv\Commands\EnvCommand;

class EnvServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-env.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([EnvCommand::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-env');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-env', function () {
            return new Env;
        });
    }
}
