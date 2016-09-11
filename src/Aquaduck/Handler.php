<?php

namespace Webpt\Aquaduck;

use Exception;
use Webpt\Aquaduck\ErrorHandler\ErrorHandlerUtility;

class Handler implements HandlerInterface
{
    /**
     * @param callable $middleware
     * @param mixed $subject
     * @param mixed $err
     * @param callable $next
     * @return mixed
     */
    public function __invoke(
        callable $middleware,
        $subject,
        $err,
        callable $next
    ) {
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
