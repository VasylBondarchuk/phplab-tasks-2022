<?php

namespace src\oop\app\src\Models;

use Exception;
use src\oop\app\src\ScrapperFactory;

class Movies
{
    const MOVIES_DATA_FILE_NAME = 'movies_data.php';

    /**
     * @var array
     */
    private array $moviesData;

    /**
     */
    public function __construct()
    {
        $this->moviesData = self::includeMoviesDataFile();
    }

    /**
     * @return array
     */
    public function getAllMovies() : array
    {
        $movies = [];
        foreach ($this->moviesData as $movieData) {
            $movies[] = $this->safeMovieCreation($movieData['domain'], $movieData['url']);
        }
        // Array with at least one movie object should be returned
        return $movies ?: [new Movie()];
    }

    /**
     * @param string $domain
     * @param string $url
     * @return MovieInterface
     */
    function safeMovieCreation(string $domain, string $url): MovieInterface
    {
        try {
            $scrapperFactory = new ScrapperFactory();
            $movie = $scrapperFactory->create($domain)->getMovie($url);
        } catch (Exception $e) {
            echo "Error of scrapping of webpage $url: ",  $e->getMessage(). '<br>';
            // default Movie object
            $movie = new Movie();
        }
        return $movie;
    }

    private static function includeMoviesDataFile(): array
    {
        $moviesUrls = [];
        $moviesDataFilepath = dirname(__FILE__). DIRECTORY_SEPARATOR. self::MOVIES_DATA_FILE_NAME;
        if(file_exists($moviesDataFilepath)){
            $moviesUrls = include_once($moviesDataFilepath);
        }
        else echo "File " . $moviesDataFilepath . " can not be opened or doesn't exist";
        return $moviesUrls;
    }
}