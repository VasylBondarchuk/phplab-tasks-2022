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
    private string $title;

    /**
     * @var string
     */
    private string $poster;

    /**
     * @var string
     */
    private string $description;

    /**
     * @param string $title
     * @param string $poster
     * @param string $description
     */
    public function __construct(string $title, string $poster, string $description)
    {
        $this->title = $title;
        $this->poster = $poster;
        $this->description = $description;
    }

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
        $this->title = $title;
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
        $this->poster = $poster;
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
        $this->description = $description;
    }
}