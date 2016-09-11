<?php

namespace Webpt\Aquaduck\Middleware;

interface MiddlewareInterface
{
    /**
     * @param mixed $subject
     * @param callable $next
     * @return mixed
     */
    public function __invoke($subject, callable $next = null);
}
