<?php

namespace Webpt\Aquaduck;

interface PriorityBindableInterface
{
    /**
     * @param ErrorHandler\ErrorHandlerInterface|MiddlewareInterface|callable $middleware
     * @param int $priority
     */
    public function bind($middleware, $priority = 1);
}
