<?php

namespace Webpt\AquaduckTest;

use Webpt\Aquaduck\FinalHandler;

class FinalHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FinalHandler $handler
     */
    private $handler;

    protected function setUp()
    {
        $this->handler = new FinalHandler();
    }

    public function testWrapsErrorException()
    {
        $handler = $this->handler;
        try {
            $handler(1, $prevException = new \Exception('ERROR'));
        } catch(\Exception $e) {
            $exceptionResult = $e;
        }

        $this->assertInstanceOf('Webpt\Aquaduck\Exception\RuntimeException', $exceptionResult);
        $this->assertSame($prevException, $exceptionResult->getPrevious());
    }

    public function testWrapsErrorString()
    {
        $handler = $this->handler;
        try {
            $handler(1, 'ERROR-MESSAGE');
        } catch(\Exception $e) {
            $exceptionResult = $e;
        }

        $this->assertInstanceOf('Webpt\Aquaduck\Exception\RuntimeException', $exceptionResult);
        $this->assertEquals('ERROR-MESSAGE', $exceptionResult->getMessage());
    }

    public function testReturnsIfNoErrorIsPresent()
    {
        $handler = $this->handler;
        $this->assertEquals(1, $handler(1));
    }
}
