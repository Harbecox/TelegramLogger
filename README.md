# Laravel Telegram Logger

Send Laravel logs to Telegram via Bot API.

## Installation
```bash
"repositories": {
        "telegram-logger": {
            "type": "vcs",
            "url": "https://github.com/Harbecox/TelegramLogger"
        }
    }
    
    
composer require harbecox/telegram-logger:dev-main
```

## Configuration

1. Publish config (optional):
```bash
php artisan vendor:publish --tag=telegram-logger-config
```

2. Add to `.env`:
```env
TELEGRAM_LOG_BOT_TOKEN=your_bot_token
TELEGRAM_LOG_CHAT_ID=your_chat_id
TELEGRAM_LOG_LEVEL=error
TELEGRAM_LOG_ASYNC=false
```

3. Add to `config/logging.php`:
```php
'channels' => [
    'telegram' => [
        'driver' => 'custom',
        'via' => \harbecox\TelegramLogger\TelegramLogger::class,
        'level' => config('telegram-logger.level'),
        'token' => config('telegram-logger.token'),
        'chat_id' => config('telegram-logger.chat_id'),
        'async' => config('telegram-logger.async'),
        'app_name' => config('telegram-logger.app_name'),
    ],
    
    'stack' => [
        'driver' => 'stack',
        'channels' => ['daily', 'telegram'],
    ],
],
```

## Usage
```php
use Illuminate\Support\Facades\Log;

Log::channel('telegram')->error('Error message');
Log::channel('telegram')->info('Info message', ['user_id' => 123]);
```

## Getting Telegram Credentials

1. **Bot Token**: Message @BotFather, send `/newbot`
2. **Chat ID**: Send message to bot, visit `https://api.telegram.org/bot<TOKEN>/getUpdates`

## License

MIT
