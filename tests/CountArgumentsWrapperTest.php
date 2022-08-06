<?php

use PHPUnit\Framework\TestCase;

class CountArgumentsWrapperTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    /**
     * @dataProvider provider
     */
    public function testException(array $argumentsArray)
    {
        $this->expectExceptionMessage('All arguments should be of type string');
        $this->expectException(InvalidArgumentException::class);
        $this->functions->countArgumentsWrapper($argumentsArray);
    }

    public function provider(): array
    {
        return [
            [[1,2]],
            [['string1',1]],
            [[1,'string2']],
        ];
    }
}