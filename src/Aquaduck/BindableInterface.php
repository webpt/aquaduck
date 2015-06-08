<?php

namespace Webpt\Aquaduck;

interface BindableInterface
{
    /**
     * @param ErrorHandler\ErrorHandlerInterface|MiddlewareInterface|callable $middleware
     */
    public function bind($middleware);
}
