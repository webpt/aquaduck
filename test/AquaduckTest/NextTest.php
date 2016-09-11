<?php

namespace Webpt\AquaduckTest;

use SplPriorityQueue;
use Webpt\Aquaduck\Next;

class NextTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->doneCallback = function ($subject) {
            return $subject;
        };
    }

    public function done($subject, $error)
    {
        $callback = $this->doneCallback;
        return call_user_func($callback, $subject, $error);
    }

    public function testThrowsExceptionOnInvalidDoneCallback()
    {
        if (phpversion() < '7.0') {
            $this->setExpectedException('\PHPUnit_Framework_Error');
        } else {
            $this->setExpectedException('\TypeError');
        }

        new Next(new SplPriorityQueue(), 'INVALID CALLBACK');
    }

    public function testCallsDoneCallbackOnEmptyQueue()
    {
        $queue = new SplPriorityQueue();
        $next  = new Next($queue, array($this, 'done'));
        $this->assertEquals('TestString1', $next('TestString1'));
    }

    public function testCallsNextCallbackOnQueueIfPresent()
    {
        $queue = new SplPriorityQueue();
        $queue->insert(function($subject) {
            return $subject + 5;
        }, 100);

        $queue->insert(function($subject) {
            return $subject * 5;
        }, 200);

        $next  = new Next($queue, array($this, 'done'));
        $this->assertEquals(5, $next(1));
    }
}
