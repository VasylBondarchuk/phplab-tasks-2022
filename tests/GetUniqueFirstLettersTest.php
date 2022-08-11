<?php

use PHPUnit\Framework\TestCase;

/**
 *
 */
class GetUniqueFirstLettersTest extends TestCase
{

    /**
     * Directory separator constant shorter version
     *
     */
    const DS = DIRECTORY_SEPARATOR;
    const INCLUDE_FILE_RELATIVE_PATH = self::DS .'src'. self::DS . 'web'. self::DS .'functions.php';

    /**
     * @return void
     */
    public function testPositive(): void
    {
        $expectedResult = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y'];
        $this->assertEquals($expectedResult, $this->getActualResult());
    }

    private function getActualResult(): array
    {
        require_once(getcwd() . self::INCLUDE_FILE_RELATIVE_PATH);
        return getUniqueFirstLetters(getAirports());
    }
}