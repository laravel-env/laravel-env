<?php

namespace LaravelEnv\LaravelEnv;

use Illuminate\Support\ServiceProvider;
use LaravelEnv\LaravelEnv\Commands\CompareExampleCommand;
use LaravelEnv\LaravelEnv\Commands\ValidateCommand;

class EnvServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('env.php'),
            ], 'config');

            $this->commands([ValidateCommand::class, CompareExampleCommand::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-env');
        $this->app->singleton('laravel-env', function () {
            return new Env;
        });
    }
}
