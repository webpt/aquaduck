<?php

namespace Webpt\AquaduckTest\Middleware;

use Webpt\Aquaduck\Middleware\AbstractMiddleware;

class AbstractMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    protected $middleware;

    protected function setUp()
    {
        $this->middleware = $this->getMockForAbstractClass(
            'Webpt\Aquaduck\Middleware\AbstractMiddleware'
        );
    }

    public function testDefaultOrderValue()
    {
        $this->assertEquals(
            AbstractMiddleware::ORDER_APPEND,
            $this->middleware->getOrder()
        );
    }

    public function testDefaultIsAppend()
    {
        $this->assertTrue($this->middleware->isAppend());
    }

    public function testDefaultIsPrepend()
    {
        $this->assertFalse($this->middleware->isPrepend());
    }

    public function testInvokeExecutesMiddleware()
    {
        $this->middleware->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function($data) {
                return array_map(function($value) {
                    return $value * 2;
                }, $data);
            });

        $middleware = $this->middleware;
        $result = $middleware(array(1, 2));

        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertContains(2, $result);
        $this->assertContains(4, $result);
    }

    /**
     * @expectedException \Webpt\Aquaduck\Exception\InvalidArgumentException
     */
    public function testThrowsExceptionOnInvalidCallback()
    {
        $middleware = $this->middleware;
        $middleware(array(), 'INVALID-CALLBACK');
    }

    public function testCallsNextCallableAfterExecute()
    {
        $this->middleware->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function($data) {
                return array_map(function($value) {
                    return $value * 2;
                }, $data);
            });

        $middleware = $this->middleware;
        $result = $middleware(array(1, 2), function($data) {
            return array_map(function($value) {
                return $value + 3;
            }, $data);
        });

        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertContains(5, $result);
        $this->assertContains(7, $result);
    }

    public function testCallsNextCallableBeforeExecute()
    {
        $reflectionClass = new \ReflectionClass($this->middleware);

        $reflectionProperty = $reflectionClass->getProperty('order');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->middleware, AbstractMiddleware::ORDER_PREPEND);

        $this->middleware->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function($data) {
                return array_map(function($value) {
                    return $value * 2;
                }, $data);
            });

        $middleware = $this->middleware;
        $result = $middleware(array(1, 2), function($data) {
            return array_map(function($value) {
                return $value + 3;
            }, $data);
        });

        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertContains(8, $result);
        $this->assertContains(10, $result);
    }
}
