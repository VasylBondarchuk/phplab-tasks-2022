<?php

use PHPUnit\Framework\TestCase;

class CountArgumentsTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    /**
     * @dataProvider provider
     */
    public function testCountArguments($expectedOutput,$input)
    {
        $this->assertEquals(
            $expectedOutput,
            $this->functions->countArguments(...$input));
    }

    public function provider(): array
    {
        return array(
            array(['argument_count'=> 0,'argument_values'=>[]], []),
            array(['argument_count'=> 1,'argument_values'=>['string1']], ['string1']),
            array(['argument_count'=> 2,'argument_values'=>['string1','string2']], ['string1','string2'])
        );
    }
}