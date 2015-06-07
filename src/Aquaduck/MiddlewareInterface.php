<?php

namespace Webpt\Aquaduck;

interface MiddlewareInterface
{
    public function __invoke($subject, $next = null);
}
