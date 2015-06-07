<?php

namespace Webpt\Aquaduck;

use ReflectionMethod;
use ReflectionFunction;
use Closure;

final class Utility
{
    private function __construct()
    {
    }

    public static function getNumberOfRequiredParameters($middleware)
    {
        if (!is_callable($middleware)) {
            return 0;
        }

        if (is_string($middleware) || $middleware instanceof Closure) {
            $r = new ReflectionFunction($middleware);
            return $r->getNumberOfRequiredParameters();
        }

        $class  = $middleware;
        $method = '__invoke';

        if (is_array($middleware)) {
            list($class, $method) = $middleware;
        }

        $r = new ReflectionMethod($class, $method);
        return $r->getNumberOfRequiredParameters();
    }
}
