<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | Your Telegram Bot Token from @BotFather
    |
    */
    'token' => env('TELEGRAM_LOG_BOT_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Telegram Chat ID
    |--------------------------------------------------------------------------
    |
    | Chat ID where logs will be sent
    |
    */
    'chat_id' => env('TELEGRAM_LOG_CHAT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Log Level
    |--------------------------------------------------------------------------
    |
    | Minimum log level: debug, info, notice, warning, error, critical, alert, emergency
    |
    */
    'level' => env('TELEGRAM_LOG_LEVEL', 'error'),

    /*
    |--------------------------------------------------------------------------
    | Async Sending
    |--------------------------------------------------------------------------
    |
    | Send messages asynchronously (non-blocking)
    |
    */
    'async' => env('TELEGRAM_LOG_ASYNC', false),

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | Will be included in log messages
    |
    */
    'app_name' => env('APP_NAME', 'Laravel'),
];