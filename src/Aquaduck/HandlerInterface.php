<?php

namespace Webpt\Aquaduck;

interface HandlerInterface
{
    /**
     * @param callable $middleware
     * @param mixed $subject
     * @param mixed $err
     * @param callable $next
     * @return mixed
     */
    public function __invoke(callable $middleware, $subject, $err, callable $next);
}
