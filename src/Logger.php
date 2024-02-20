<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace Arrows;

use DateTimeImmutable;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Logger\LoggerFactory;
use function Hyperf\Config\config;

/**
 * @method static void error(array|string|\Stringable $logData, string $channel = null)
 * @method static void info(array|string|\Stringable $logData, string $channel = null)
 * @method static void debug(array|string|\Stringable $logData, string $channel = null)
 * @method static void alert(array|string|\Stringable $logData, string $channel = null)
 */
final class Logger
{
    public static function __callStatic($method, array $args)
    {
        $chan = $args[1] ?? $args['channel'] ?? config('app_env');
        $text = $args[0] ?? $args['logData'] ?? '没有任何错误消息？';
        $text = is_array($text) ? JSON::encode($text) : (string)$text;
        $msg  = sprintf("%s  %s \n", (new DateTimeImmutable())->format('Y-m-d H:i:s.v'), $text);
        $c    = ApplicationContext::getContainer();
        $c->get(StdoutLoggerInterface::class)->{$method}($msg);
        $c->get(LoggerFactory::class)->get($chan)->{$method}($msg);
    }
}
