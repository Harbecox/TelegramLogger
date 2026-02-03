<?php

namespace Harbecox\TelegramLogger;

use Illuminate\Support\ServiceProvider;

class TelegramLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/telegram-logger.php' => config_path('telegram-logger.php'),
            ], 'telegram-logger-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/telegram-logger.php',
            'telegram-logger'
        );
    }
}