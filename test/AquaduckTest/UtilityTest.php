<?php

namespace Webpt\AquaduckTest;

use Webpt\Aquaduck\Utility;

class UtilityTest extends \PHPUnit_Framework_TestCase
{
    public function aFunctionWithThreeArguments($arg1, $arg2, $arg3)
    {

    }

    public function getCallables()
    {
        return array(
            array(
                function($arg1, $arg2) {},
                2
            ),
            array(
                $this->getMock('Webpt\Aquaduck\MiddlewareInterface'),
                1
            ),
            array(
                'preg_replace',
                3
            ),
            array(
                array($this, 'aFunctionWithThreeArguments'),
                3
            ),
            array(
                'REGULARSTRING',
                0
            ),
            array(
                new \stdClass(),
                0
            )
        );
    }

    /**
     * @dataProvider getCallables
     */
    public function testReturnsNumberOfArguments($test, $numArgs)
    {
        $this->assertEquals($numArgs, Utility::getNumberOfRequiredParameters($test));
    }
}
