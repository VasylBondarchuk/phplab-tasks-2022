<?php

namespace basics;

class Basics implements BasicsInterface
{
    const MINUTE_MAX_VALUE = 60;
    const QUARTER_VALUE = self::MINUTE_MAX_VALUE / 4;
    const QUARTER_NAME = ["first","second","third","fourth"];
    const YEAR_MIN_VALUE = 1900;
    const STRING_LENGTH = 6;

    /**
     * @var BasicsValidator
     */
    private BasicsValidator $validator;

    /**
     * @param BasicsValidator $validator
     */
    public function __construct(BasicsValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param  int $minute
     * @return string
     */
    public function getMinuteQuarter(int $minute): string
    {
        $this->validator->isMinutesException($minute);
        $quarterNumber = $minute == 0 ? 4 : ceil($minute / self::QUARTER_VALUE);
        return self::QUARTER_NAME[$quarterNumber - 1];
    }

    /**
     * @param  int $year
     * @return bool
     */
    public function isLeapYear(int $year): bool
    {
        $this->validator->isYearException($year);
        if ($year % 100 == 0) {
            return ($year % 400 == 0);
        }
        return ($year % 4 == 0);
    }

    /**
     * @param  string $input
     * @return bool
     */
    public function isSumEqual(string $input): bool
    {
        $this->validator->isValidStringException($input);
        $half = strlen($input) / 2;
        return array_sum(str_split(substr($input, 0, $half)))
            == array_sum(str_split(substr($input, -$half)));
    }
}