<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Logger;

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Bramus\Monolog\Formatter\ColorSchemes\DefaultScheme;
use InvalidArgumentException;
use MicroPHP\Framework\Logger\Enum\LoggerHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Stringable;

class Logger
{
    private MonologLogger $logger;

    public function __construct(MonologLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array<LoggerHandler> $handlers
     */
    public static function get(array $handlers = [LoggerHandler::STDOUT, LoggerHandler::ROTATING_FILE], string $channel = 'app'): Logger
    {
        $monolog = new MonologLogger($channel);
        foreach ($handlers as $handlerType) {
            switch ($handlerType) {
                case LoggerHandler::STDOUT:
                    $handler = new StreamHandler('php://stdout');
                    $handler->setFormatter(new ColoredLineFormatter(new DefaultScheme(), allowInlineLineBreaks: true, ignoreEmptyContextAndExtra: true, includeStacktraces: true));
                    break;
                case LoggerHandler::ROTATING_FILE:
                    $handler = new RotatingFileHandler(base_path("runtime/logs/{$channel}.log"), 7);
                    $logDir = base_path('runtime/logs');
                    if (! is_dir($logDir)) {
                        mkdir($logDir, 0755, true);
                    }
                    $handler->setFormatter(new JsonFormatter(ignoreEmptyContextAndExtra: true, includeStacktraces: true));
                    break;
                default:
                    throw new InvalidArgumentException(sprintf('Handler type "%s" is not supported.', $handlerType->value));
            }
            $monolog->pushHandler($handler);
        }
        return new Logger($monolog);
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    public function getMonoLogger(): MonologLogger
    {
        return $this->logger;
    }
}
