<?php

namespace harbecox\TelegramLogger;

use Monolog\Logger;

class TelegramLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('telegram');
        $handler = new TelegramLogHandler($config);
        $logger->pushHandler($handler);

        return $logger;
    }
}