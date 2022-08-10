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

    /**
     * @return void
     */
    public function testPositive()
    {
        require_once(getcwd() . self::DS .'src'. self::DS . 'web'. self::DS .'functions.php');
        $actualResult = getUniqueFirstLetters(getAirports());
        $expectedResult = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y'];
        $this->assertEquals($expectedResult, $actualResult);
    }
}