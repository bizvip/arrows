<?php
/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows\Exception;

use App\Constants\ErrCode;
use Throwable;

final class EventException extends \RuntimeException
{
    /**
     * @param  int  $code
     * @param  mixed  $message
     * @param  \Throwable|null  $previous
     */
    public function __construct(int $code = 0, $message = null, Throwable $previous = null)
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
