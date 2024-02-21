<?php
/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows\Exception\Handler;

use App\Constants\ErrCode;
use App\Service\Auth\Exceptions\TokenInvalidException;
use GiocoPlus\JWTAuth\Exception\JWTException;
use GiocoPlus\JWTAuth\Exception\TokenValidException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;

final class AuthExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected StdoutLoggerInterface $logger;

    /**
     * @param  \Hyperf\Contract\StdoutLoggerInterface  $logger
     */
    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param  \Throwable  $throwable
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        $code = ErrCode::HTTP_AUTH_FAILED;
        $msg  = $throwable->getMessage();

        if ($throwable instanceof TokenInvalidException) {
            $msg = empty($msg) ? ErrCode::getMessage(ErrCode::HTTP_AUTH_FAILED) : $msg;
        }

        $r = Json::encode(
            ['code' => $code, 'msg' => $msg, 'data' => null,],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );

        return $response
            ->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new SwooleStream($r));
    }

    /**
     * @param  \Throwable  $throwable
     *
     * @return bool
     */
    public function isValid(\Throwable $throwable): bool
    {
        return $throwable instanceof TokenInvalidException
            || $throwable instanceof TokenValidException
            || $throwable instanceof JWTException;
    }
}
