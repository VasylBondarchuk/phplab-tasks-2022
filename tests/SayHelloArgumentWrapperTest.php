<?php

use PHPUnit\Framework\TestCase;

class SayHelloArgumentWrapperTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    /**
     * @dataProvider provider
     */
    public function testException($arg)
    {
        $this->expectExceptionMessage('Only '. $this->functions->getAllowedTypesAsString() . ' are allowed. Input type was: ' . gettype($arg));
        $this->expectException(InvalidArgumentException::class);
        $this->functions->sayHelloArgumentWrapper($arg);
    }

    public function provider(): array
    {
        return [
            [new class {}],
            [null],
            [[1,2,3]],
            [tmpfile()]
        ];
    }
}