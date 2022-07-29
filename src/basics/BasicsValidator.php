<?php

namespace basics;

use InvalidArgumentException;

class BasicsValidator implements BasicsValidatorInterface
{
    /**
     * @param  int $minute
     * @return void
     */
    public function isMinutesException(int $minute): void
    {
        if ($minute < 0) {
            throw new InvalidArgumentException(
                "Minute value should not be negative. Input was: ". $minute
            );
        }
        if ($minute > Basics::MINUTE_MAX_VALUE) {
            throw new InvalidArgumentException(
                "Minute value should not exceed "
                . Basics::MINUTE_MAX_VALUE . " Input was: " . $minute
            );
        }
    }

    /**
     * @param  int $year
     * @return void
     */
    public function isYearException(int $year): void
    {
        if ($year < 0) {
            throw new InvalidArgumentException(
                'Year value should not be negative. Input was: '.$year
            );
        }
        if ($year < Basics::YEAR_MIN_VALUE) {
            throw new InvalidArgumentException(
                "Year value should not be less than "
                . Basics::YEAR_MIN_VALUE . " Input was: ". $year
            );
        }
    }

    /**
     * @param  string $input
     * @return void
     */
    public function isValidStringException(string $input): void
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException(
                "Non numerical characters are not allowed. Input was: " . $input
            );
        }
        if (strlen($input) != Basics::STRING_LENGTH) {
            throw new InvalidArgumentException(
                "String's length should be equal to "
                . Basics::STRING_LENGTH . " Input's length was: " . strlen($input)
            );
        }
    }
}