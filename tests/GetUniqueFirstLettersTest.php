<?php

use PHPUnit\Framework\TestCase;

/**
 *
 */
class GetUniqueFirstLettersTest extends TestCase
{

    const DS = DIRECTORY_SEPARATOR;
    const REL_PATH_TO_FILES = self::DS . 'src' . self::DS . 'web' . self::DS;

    /**
     * @return void
     */
    public function testPositive(): void
    {
        $expectedResult = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y'];
        $this->assertEquals($expectedResult, $this->getActualResult());
    }

    /**
     * @return array
     */
    private function getActualResult(): array
    {
        $path = getcwd() . self::REL_PATH_TO_FILES;
        require_once($path . './functions.php');
        $airports =  require_once($path . './airports.php');
        return getUniqueFirstLetters($airports);
    }
}