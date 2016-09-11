<?php

namespace Webpt\Aquaduck;

use SplPriorityQueue;

class Next
{
    private $queue;
    private $done;

    /**
     * Next constructor.
     * @param SplPriorityQueue $queue
     * @param callable $done
     * @param callable $handler
     */
    public function __construct(SplPriorityQueue $queue, callable $done, callable $handler = null)
    {
        $this->queue   = clone $queue;
        $this->done    = $done;
        $this->handler = $handler ?: new Handler();
    }

    public function __invoke(
        $subject,
        $err = null
    ) {
        $handler  = $this->handler;
        $done     = $this->done;

        if ($this->queue->isEmpty()) {
            return call_user_func($done, $subject, $err);
        }
        $layer  = $this->queue->extract();
        return $handler($layer, $subject, $err, $this);
    }
}
