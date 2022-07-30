<?php

namespace strings;

/**
 *
 */
class Strings implements StringsInterface
{
    /**
     * @param  string $input
     * @return string
     */
    public function snakeCaseToCamelCase(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }

    /**
     * @param  string $input
     * @return string
     */
    public function mirrorMultibyteString(string $input): string
    {
        $reversed = '';
        $words = explode(' ', $input);
        foreach ($words as $word) {
            $reversed .= $this->mb_strrev($word). ' ';
        }
        return trim($reversed);
    }

    /**
     * @param  string $noun
     * @return string
     */
    public function getBrandName(string $noun): string
    {
        $prefix = "The";
        $brand = $prefix . " " . ucfirst($noun);
        // if a noun STARTS and ENDS with the same letter
        if (substr($noun, 0, 1) == substr($noun, -1)) {
            $brand = ucfirst($noun) . substr($noun, 1);
        }
        return $brand;
    }

    /**
     * Reverse multibyte string
     *
     * @param  string $input
     * @return string
     */
    public function mb_strrev(string $input): string
    {
        $reversed = '';
        for ($i = mb_strlen($input); $i >= 0; $i--) {
            $reversed .= mb_substr($input, $i, 1);
        }
        return $reversed;
    }
}