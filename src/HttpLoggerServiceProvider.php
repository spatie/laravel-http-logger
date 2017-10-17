<?php

namespace Spatie\HttpLogger;

use Illuminate\Support\ServiceProvider;

class HttpLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/http-logger.php' => config_path('http-logger.php'),
            ], 'config');
        }

        $this->app->singleton(LogProfile::class, config('http-logger.log_profile'));
        $this->app->singleton(LogOutput::class, config('http-logger.log_output'));
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/http-logger.php', 'http-logger');
    }
}
