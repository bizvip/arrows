<?php
/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

namespace Arrows\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

final class HttpExceptionHandler extends ExceptionHandler
{
    // protected StdoutLoggerInterface $logger;
    // protected FormatterInterface $formatter;

    // public function __construct(StdoutLoggerInterface $logger, FormatterInterface $formatter)
    // {
    //     $this->logger    = $logger;
    //     $this->formatter = $formatter;
    // }

    public function handle(\Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        return $response->withStatus($throwable->getStatusCode())->withBody(
            new SwooleStream($throwable->getMessage())
        );
    }

    public function isValid(\Throwable $throwable): bool
    {
        return $throwable instanceof HttpException;
    }
}
