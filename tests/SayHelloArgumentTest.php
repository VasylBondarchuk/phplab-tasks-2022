<?php

use PHPUnit\Framework\TestCase;

class SayHelloArgumentTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    /**
     * @dataProvider provider
     */
    public function testHelloArgument($expected,$arg)
    {
        $this->assertEquals($expected, $this->functions->sayHelloArgument($arg));
    }

    public function provider(): array
    {
        return array(
            array("Hello World", "World"),
            array("Hello 123", 123),
            array("Hello 1", true)
        );
    }
}