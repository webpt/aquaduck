<?php

namespace Webpt\Aquaduck;

use Webpt\Aquaduck\Exception\InvalidArgumentException;
use SplPriorityQueue;
use Webpt\Aquaduck\Middleware\MiddlewareInterface;

class Aquaduck implements MiddlewareInterface, PriorityBindableInterface
{
    private $serial = PHP_INT_MAX;

    public function __construct()
    {
        $this->queue = new SplPriorityQueue();
    }

    public function __invoke($subject, $out = null)
    {
        if ($out !== null && !is_callable($out)) {
            throw new InvalidArgumentException('Final Handler must be callable');
        }

        $done   = $out ?: new FinalHandler();
        $next   = new Next($this->queue, $done, new Handler);
        return $next($subject);
    }

    public function bind($middleware, $priority = 1)
    {
        if (!is_callable($middleware)) {
            throw new InvalidArgumentException('Middleware must be callable');
        }

        if (!is_array($priority)) {
            $priority = array($priority, $this->serial--);
        }

        $this->queue->insert($middleware, $priority);
        return $this;
    }
}
