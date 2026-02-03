<?php

namespace Harbecox\TelegramLogger;

use Monolog\Logger;

class TelegramLogger
{
    /**
     * Create a custom Monolog instance.
     * @throws \Exception
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('telegram');
        $handler = new TelegramLogHandler($config);
        $logger->pushHandler($handler);

        return $logger;
    }
}