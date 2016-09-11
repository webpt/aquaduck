<?php

namespace Webpt\Aquaduck;

use Webpt\Aquaduck\ErrorHandler\ErrorHandlerInterface;
use Webpt\Aquaduck\Middleware\MiddlewareInterface;

interface PriorityBindableInterface
{
    /**
     * @param ErrorHandlerInterface|MiddlewareInterface|callable $middleware
     * @param int $priority
     */
    public function bind(callable $middleware, $priority = 1);
}
