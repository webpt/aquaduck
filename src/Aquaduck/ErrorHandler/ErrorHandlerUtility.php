<?php

namespace Webpt\Aquaduck\ErrorHandler;

use Webpt\Aquaduck\Middleware\MiddlewareInterface;
use Webpt\Aquaduck\Utility;

final class ErrorHandlerUtility
{
    private function __construct(){}

    public static function isErrorHandler($middleware)
    {
        if ($middleware instanceof ErrorHandlerInterface) {
            return true;
        }

        if ($middleware instanceof MiddlewareInterface) {
            return false;
        }

        if (Utility::getNumberOfRequiredParameters($middleware) === 3) {
            return true;
        }

        return false;
    }
}
