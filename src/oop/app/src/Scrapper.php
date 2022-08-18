<?php

namespace src\oop\app\src;

use src\oop\app\src\Parsers\ParserInterface;
use src\oop\app\src\Transporters\TransportInterface;
use src\oop\app\src\Models\MovieInterface;
use src\oop\app\src\Models\Movie;

/**
 * Create Class - Scrapper with method getMovie().
 * getMovie() - should return Movie Class object.
 *
 * Note: Use next namespace for Scrapper Class - "namespace src\oop\app\src;"
 * Note: Dont forget to create variables for TransportInterface and ParserInterface objects.
 * Note: Also you can add your methods if needed.
 */

class Scrapper
{

    /**
     * @var TransportInterface
     */
    public TransportInterface $transportInterface;
    /**
     * @var ParserInterface
     */
    public ParserInterface $parserInterface;

    /**
     * @param TransportInterface $transportInterface
     * @param ParserInterface $parserInterface
     */
    public function __construct(TransportInterface $transportInterface, ParserInterface $parserInterface)
    {
        $this->transportInterface = $transportInterface;
        $this->parserInterface = $parserInterface;
    }

    /**
     * @return TransportInterface
     */
    public function getTransportInterface(): TransportInterface
    {
        return $this->transportInterface;
    }

    /**
     * @param TransportInterface $transportInterface
     */
    public function setTransportInterface(TransportInterface $transportInterface): void
    {
        $this->transportInterface = $transportInterface;
    }

    /**
     * @return ParserInterface
     */
    public function getParserInterface(): ParserInterface
    {
        return $this->parserInterface;
    }

    /**
     * @param ParserInterface $parserInterface
     */
    public function setParserInterface(ParserInterface $parserInterface): void
    {
        $this->parserInterface = $parserInterface;
    }

    /**
     * @param string $url
     * @return Movie
     */
    public function getMovie(string $url): Movieinterface
    {
        $movie = new Movie();
        $movie->setTitle($this->getMovieParamValue($url,'title'));
        $movie->setDescription($this->getMovieParamValue($url,'description'));
        $movie->setPoster($this->getMovieParamValue($url,'poster'));
        return $movie;
    }

    private function getMovieParamValue(string $url, string $param)
    {
        return $this->getParserInterface()
            ->parseContent($this->getTransportInterface()->getContent($url))[$param];
    }
}
