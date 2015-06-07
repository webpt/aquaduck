<?php

namespace Webpt\Aquaduck;

use Exception;
use Webpt\Aquaduck\ErrorHandler\ErrorHandlerUtility;
use Webpt\Aquaduck\Exception\InvalidArgumentException;

class Handler implements HandlerInterface
{
    public function __invoke(
        $middleware,
        $subject,
        $err,
        $next
    ) {
        if (!is_callable($middleware)) {
            throw new InvalidArgumentException('$middleware must be callable');
        }

        if (!is_callable($next)) {
            throw new InvalidArgumentException('$next must be callable');
        }

        $hasError = (null !== $err);
        $isErrorHandler = ErrorHandlerUtility::isErrorHandler($middleware);

        try {
            if ($hasError && $isErrorHandler) {
                return call_user_func($middleware, $err, $subject, $next);
            }
            if (!$hasError && !$isErrorHandler) {
                return call_user_func($middleware, $subject, $next);
            }
        } catch (Exception $e) {
            $err = $e;
        }
        return $next($subject, $err);
    }
}
