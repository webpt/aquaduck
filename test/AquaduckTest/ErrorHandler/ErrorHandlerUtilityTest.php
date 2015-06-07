<?php

namespace Webpt\AquaduckTest\ErrorHandler;

use Webpt\Aquaduck\ErrorHandler\ErrorHandlerUtility;

class ErrorHandlerUtilityTest extends \PHPUnit_Framework_TestCase
{
    public function aFunctionWithThreeArguments($arg1, $arg2, $arg3)
    {

    }

    public function aFunctionWithOneArgument($arg1)
    {

    }

    public function getMiddlewares()
    {
        return array(
            array(
                $this->getMock('Webpt\Aquaduck\ErrorHandler\ErrorHandlerInterface'),
                true
            ),
            array(
                $this->getMock('Webpt\Aquaduck\MiddlewareInterface'),
                false
            ),
            array(
                function() {},
                false
            ),
            array(
                function($arg1, $arg2, $arg3) {},
                true
            ),
            array(
                array($this, 'aFunctionWithThreeArguments'),
                true
            ),
            array(
                array($this, 'aFunctionWithOneArgument'),
                false
            ),
            array(
                'REGULARSTRING',
                false
            ),
            array(
                new \stdClass(),
                false
            )
        );
    }

    /**
     * @dataProvider getMiddlewares
     */
    public function testIsErrorHandlerReturnsBoolean($test, $result)
    {
        $this->assertEquals($result, ErrorHandlerUtility::isErrorHandler($test));
    }
}
