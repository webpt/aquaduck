<?php

namespace Webpt\AquaduckTest;

use Webpt\Aquaduck\Aquaduck;

class AquaduckTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Aquaduck $aquaduck
     */
    private $aquaduck;

    protected function setUp()
    {
        $this->aquaduck = new Aquaduck();
    }

    public function testThrowExceptionOnInvalidMiddleware()
    {
        if (phpversion() < '7.0') {
            $this->setExpectedException('\PHPUnit_Framework_Error');
        } else {
            $this->setExpectedException('\TypeError');
        }
        
        $this->aquaduck->bind('totally-invalid-argument');
    }

    public function testThrowExceptionOnInvalidFinalHandler()
    {
        if (phpversion() < '7.0') {
            $this->setExpectedException('\PHPUnit_Framework_Error');
        } else {
            $this->setExpectedException('\TypeError');
        }

        $aquaduck = $this->aquaduck;
        $aquaduck(1, 'totally-invalid-argument');
    }

    public function testMiddlewarePriorityReturnsFIFO()
    {
        $aquaduck = $this->aquaduck;
        $aquaduck->bind(function($subject, $next) {
            return $next($subject * 5);
        });
        $aquaduck->bind(function($subject, $next) {
            return $next($subject + 5);
        });

        $this->assertEquals(10, $aquaduck(1));
    }

    public function testMiddlewarePriority()
    {
        $aquaduck = $this->aquaduck;
        $aquaduck->bind(function($subject, $next) {
            return $next($subject * 5);
        }, 100);
        $aquaduck->bind(function($subject, $next) {
            return $next($subject + 5);
        }, 200);

        $this->assertEquals(30, $aquaduck(1));
    }
}
