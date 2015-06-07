<?php

namespace Webpt\AquaduckTest;

use Webpt\Aquaduck\Handler;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Handler $handler
     */
    private $handler;

    protected function setUp()
    {
        $this->handler = new Handler();
    }

    /**
     * @expectedException \Webpt\Aquaduck\Exception\InvalidArgumentException
     */
    public function testThrowExceptionOnInvalidMiddlewareCallback()
    {
        $handler = $this->handler;
        $handler('totally-invalid-argument', 1, null, function() {});
    }

    /**
     * @expectedException \Webpt\Aquaduck\Exception\InvalidArgumentException
     */
    public function testThrowExceptionOnInvalidNextCallback()
    {
        $handler = $this->handler;
        $handler(function() {}, 1, null, 'totally-invalid-argument');
    }

    public function testMiddlewareIsSkippedOnError()
    {
        $handler = $this->handler;
        $result = $handler(
            function() {
                return false;
            },
            1,
            new \Exception('ERROR'),
            function() {
                return true;
            }
        );

        $this->assertTrue($result);
    }

    public function testErrorHandlerTriggeredIfPresentOnError()
    {
        $handler = $this->handler;
        $result = $handler(
            function($error, $subject, $next) {
                return false;
            },
            1,
            new \Exception('ERROR'),
            function() {
                return true;
            }
        );

        $this->assertFalse($result);
    }

    public function testExceptionPassedToNext()
    {
        $exception = new \Exception('ERROR');

        $handler = $this->handler;
        $result = $handler(
            function() use ($exception){
                throw $exception;
            },
            1,
            null,
            function($subject, $error) {
                return $error;
            }
        );

        $this->assertSame($exception, $result);
    }
}
