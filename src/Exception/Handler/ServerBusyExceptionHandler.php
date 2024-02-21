<?php
/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

namespace Arrows\Exception\Handler;

use Arrows\Constants\ErrCode;
use Arrows\Exception\ServerBusyException;
use Arrows\JSON;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\RateLimit\Exception\RateLimitException;
use Psr\Http\Message\ResponseInterface;
use Swow\Psr7\Message\ResponsePlusInterface;
use Throwable;

final class ServerBusyExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface|ResponsePlusInterface $response): ResponseInterface
    {
        $msg = $throwable->getMessage();

        $r = $throwable instanceof RateLimitException
            ? ['code' => ErrCode::HTTP_RATE_LIMIT, 'msg' => '' !== $msg ? $msg : ErrCode::getMessage(ErrCode::HTTP_RATE_LIMIT), 'data' => null,]
            : ['code' => ErrCode::HTTP_SERVER_BUSY, 'msg' => '' !== $msg ? $msg : ErrCode::getMessage(ErrCode::HTTP_SERVER_BUSY), 'data' => null,];

        $this->stopPropagation();

        return $response->withStatus($r['code'])
            ->withHeader('Content-Type', 'application/json')->withHeader('Retry-After', 3)
            ->withBody(new SwooleStream(JSON::encode($r)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ServerBusyException || $throwable instanceof RateLimitException;
    }
}
