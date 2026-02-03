<?php

namespace harbecox\TelegramLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Http;
use Exception;

class TelegramLogHandler extends AbstractProcessingHandler
{
    protected string $botToken;
    protected string $chatId;
    protected bool $async;
    protected ?string $appName;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level'] ?? 'debug');

        parent::__construct($level, true);

        $this->botToken = $config['token'] ?? '';
        $this->chatId = $config['chat_id'] ?? '';
        $this->async = $config['async'] ?? false;
        $this->appName = $config['app_name'] ?? config('app.name');

        if (empty($this->botToken) || empty($this->chatId)) {
            throw new Exception('Telegram bot token and chat_id are required');
        }
    }

    protected function write(LogRecord $record): void
    {
        try {
            $message = $this->formatMessage($record);

            $payload = [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ];

            if ($this->async) {
                Http::async()
                    ->timeout(5)
                    ->post($this->getApiUrl(), $payload);
            } else {
                Http::timeout(5)
                    ->post($this->getApiUrl(), $payload);
            }
        } catch (Exception $e) {
            // Не прерываем выполнение приложения при ошибке отправки
            // Можно логировать в другой канал, если нужно
        }
    }

    protected function getApiUrl(): string
    {
        return "https://api.telegram.org/bot{$this->botToken}/sendMessage";
    }

    protected function formatMessage(LogRecord $record): string
    {
        $emoji = $this->getEmojiForLevel($record->level);

        $message = "{$emoji} <b>{$record->level->name}</b>";

        if ($this->appName) {
            $message .= " | {$this->appName}";
        }

        $message .= "\n\n";
        $message .= "<b>Message:</b> " . htmlspecialchars($record->message) . "\n";

        if (!empty($record->context)) {
            $message .= "\n<b>Context:</b>\n";
            $message .= "<pre>" . htmlspecialchars(
                    json_encode($record->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                ) . "</pre>\n";
        }

        if (isset($record->extra['file'])) {
            $message .= "\n<b>File:</b> {$record->extra['file']}";
            if (isset($record->extra['line'])) {
                $message .= ":{$record->extra['line']}";
            }
            $message .= "\n";
        }

        $message .= "\n<b>Time:</b> {$record->datetime->format('Y-m-d H:i:s')}";

        if (isset($record->extra['url'])) {
            $message .= "\n<b>URL:</b> {$record->extra['url']}";
        }

        if (isset($record->extra['ip'])) {
            $message .= "\n<b>IP:</b> {$record->extra['ip']}";
        }

        return mb_substr($message, 0, 4096); // Telegram limit
    }

    protected function getEmojiForLevel($level): string
    {
        return match($level->value) {
            Logger::DEBUG => '🔍',
            Logger::INFO => 'ℹ️',
            Logger::NOTICE => '📢',
            Logger::WARNING => '⚠️',
            Logger::ERROR => '❌',
            Logger::CRITICAL => '🔥',
            Logger::ALERT => '🚨',
            Logger::EMERGENCY => '💀',
            default => '📝',
        };
    }
}