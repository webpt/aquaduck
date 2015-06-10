<?php

namespace Webpt\Aquaduck\Middleware;

interface MiddlewareInterface
{
    public function __invoke($subject, $next = null);
}
