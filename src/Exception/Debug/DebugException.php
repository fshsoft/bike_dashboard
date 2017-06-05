<?php

namespace Bike\Dashboard\Exception\Debug;

use Bike\Dashboard\Error\ErrorCode;

class DebugException extends \Exception implements DebugExceptionInterface
{
    public function __construct($message = null)
    {
        if (!$message) {
            $message = '系统错误';
        }

        parent::__construct($message, ErrorCode::DEBUG_ERROR);
    }
}

