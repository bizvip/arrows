<?php
/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows\Exception\Handler;

use App\Constants\ErrCode;
use Arrows\Exception\BusinessException;
use Hyperf\Codec\Json;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class FinallyExceptionHandler extends ExceptionHandler
{
//    protected StdoutLoggerInterface $logger;
//
//    public function __construct(StdoutLoggerInterface $logger)
//    {
//        $this->logger = $logger;
//    }

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        if ($throwable instanceof BusinessException) {
            $msg  = $throwable->getMessage();
            $code = 500;
            goto resp;
        }

        if ($throwable instanceof ValidationException) {
            $msg  = ErrCode::getMessage(
                code     : ErrCode::VALIDATED_FAILED,
                translate: ['reason' => $throwable->validator->errors()->first(),]
            );
            $code = ErrCode::VALIDATED_FAILED;
            goto resp;
        }

        if ($throwable instanceof NotFoundHttpException) {
            $msg  = ErrCode::getMessage(ErrCode::HTTP_NOT_FOUND);
            $code = ErrCode::HTTP_NOT_FOUND;
            goto resp;
        }

        resp:
        if (isset($msg, $code)) {
            $contents = Json::encode(data: ['code' => $code, 'message' => $msg, 'data' => null, 'success' => false]);

            return $response->withHeader('Server', 'N1')
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Access-Control-Allow-Headers', 'accept-language,authorization,lang,uid,token,Keep-Alive,User-Agent,Cache-Control,Content-Type')
                ->withAddedHeader('content-type', 'application/json; charset=UTF-8')
                ->withStatus(200)
                ->withBody(new SwooleStream($contents));
        }

        // undefined exceptions
        \console()->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        \container()
            ->get(LoggerFactory::class)
            ->get(\Hyperf\Config\config('app_serv'))
            ->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));

        $format = ['success' => false, 'code' => 500, 'message' => $throwable->getMessage()];

        return $response->withHeader('Server', 'N1')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Headers', 'accept-language,authorization,lang,uid,token,Keep-Alive,User-Agent,Cache-Control,Content-Type')
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withStatus(500)
            ->withBody(new SwooleStream(Json::encode($format)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
