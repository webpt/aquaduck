<?php

namespace Webpt\Aquaduck;

use SplPriorityQueue;
use Webpt\Aquaduck\Middleware\MiddlewareInterface;

class Aquaduck implements MiddlewareInterface, PriorityBindableInterface
{
    private $serial = PHP_INT_MAX;
    
    /** @var SplPriorityQueue */
    private $queue;

    public function __construct()
    {
        $this->queue = new SplPriorityQueue();
    }

    /**
     * @param mixed $subject
     * @param callable $out
     * @return mixed
     */
    public function __invoke($subject, callable $out = null)
    {
        $done   = $out ?: new FinalHandler();
        $next   = new Next($this->queue, $done, new Handler);
        return $next($subject);
    }

    /**
     * @param callable $middleware
     * @param mixed[]|int $priority
     * @return $this
     */
    public function bind(callable $middleware, $priority = 1)
    {
        if (!is_array($priority)) {
            $priority = array($priority, $this->serial--);
        }

        $this->queue->insert($middleware, $priority);
        return $this;
    }
}
