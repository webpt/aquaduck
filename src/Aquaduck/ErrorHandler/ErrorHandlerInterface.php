<?php

namespace Webpt\Aquaduck\ErrorHandler;

interface ErrorHandlerInterface
{
    public function __invoke($error, $subject, $next = null);
}
