<?php
/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows\Exception;

use App\Constants\ErrCode;
use Hyperf\Server\Exception\ServerException;
use Throwable;

final class BusinessException extends ServerException
{
    public function __construct(int $code = 200, string|array|null $message = null, Throwable $previous = null)
    {
        if (null === $message) {
            $message = ErrCode::getMessage($code);
        }
        if (is_array($message)) {
            $message = ErrCode::getMessage($code, $message);
        }

        parent::__construct($message, $code, $previous);
    }
}
