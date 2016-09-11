<?php

namespace Webpt\Aquaduck\ErrorHandler;

interface ErrorHandlerInterface
{
    /**
     * @param $error
     * @param mixed $subject
     * @param callable $next
     * @return mixed
     */
    public function __invoke($error, $subject, callable $next = null);
}
