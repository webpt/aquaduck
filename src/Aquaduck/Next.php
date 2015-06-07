<?php

namespace Webpt\Aquaduck;

use SplPriorityQueue;
use Webpt\Aquaduck\Exception\InvalidArgumentException;

class Next
{
    private $queue;
    private $done;

    public function __construct(SplPriorityQueue $queue, $done, $handler = null)
    {
        if (!is_callable($done)) {
            throw new InvalidArgumentException('2nd constructor argument must be callable');
        }

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
            return $done($subject, $err);
        }
        $layer  = $this->queue->extract();
        return $handler($layer, $subject, $err, $this);
    }
}
