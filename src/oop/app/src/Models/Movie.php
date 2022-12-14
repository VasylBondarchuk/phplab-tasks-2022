<?php
/**
 * Create Class - Movie.
 * Implement the MovieInterface and following methods:
 * getTitle(), setTitle(), getPoster(), setPoster(), getDescription(), setDescription()
 * for record and read Movie data.
 *
 * Note: Don't forget to create properties for storing Movie data.
 * Note: Use next namespace for Movie Class - "namespace src\oop\app\src\Models;" (Like in this Interface)
 *
 * Note: You need to inject this Class somewhere in the code to get Movie object with film data.
 * Think about where and how to do it better!!!
 */

namespace src\oop\app\src\Models;

/**
 *
 */
class Movie implements MovieInterface
{
    /**
     * @var string
     */
    private string $title = self::DEFAULT_TITLE;

    /**
     * @var string
     */
    private string $poster = self::DEFAULT_POSTER;

    /**
     * @var string
     */
    private string $description = self::DEFAULT_DESCRIPTION;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title ?: self::DEFAULT_TITLE;
    }

    /**
     * @return string
     */
    public function getPoster(): string
    {
        return $this->poster;
    }

    /**
     * @param string $poster
     */
    public function setPoster(string $poster): void
    {
        $this->poster = $poster ?: self::DEFAULT_POSTER;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description ?: self::DEFAULT_DESCRIPTION;
    }

    /**
     * Abstract setter
     *
     * @param string $paramName
     * @param string $paraValue
     * @return void
     */
    public function set(string $paramName, string $paraValue): void
    {
        $setterName = 'set' . ucfirst($paramName);
        $this->$setterName($paraValue);
    }

    /**
     * @return array
     */
    public function getPropertiesNames(): array
    {
        return array_keys(get_object_vars($this));
    }
}