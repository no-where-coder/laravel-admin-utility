<?php

namespace Nowhere\AdminUtility\Support;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Psr\Log\LoggerInterface;

class AdminLogger
{
    public static function get(): LoggerInterface
    {
        $logger = new Logger('admin-utility');
        $logger->pushHandler(new NullHandler()); // Swallow all logs
        return $logger;
    }
}
