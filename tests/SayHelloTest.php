<?php

use PHPUnit\Framework\TestCase;

class SayHelloTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    public function testExpectHelloActualHello()
    {
        $this->expectOutputString($this->functions->sayHello());
        print 'Hello';
    }
}