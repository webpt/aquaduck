<?php

namespace Webpt\Aquaduck;

interface HandlerInterface
{
    public function __invoke($middleware, $subject, $err, $next);
}
