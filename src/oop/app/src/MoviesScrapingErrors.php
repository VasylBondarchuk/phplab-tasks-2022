<?php

namespace src\oop\app\src;

class MoviesScrapingErrors
{
    private array $errors;

    /**
     * @param array $errors
     */
    public function __construct(array $errors = [])
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getAllErrors() : array
    {
        return $this->errors;
    }

    /**
     * @param $error
     */
    public function addError($error): void
    {
        array_push($this->errors, $error);

    }

}