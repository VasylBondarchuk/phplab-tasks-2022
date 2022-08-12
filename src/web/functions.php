<?php
/**
 * The $airports variable contains array of arrays of airports (see airports.php)
 * What can be put instead of placeholder so that function returns the unique first letter of each airport name
 * in alphabetical order
 *
 * Create a PhpUnit test (GetUniqueFirstLettersTest) which will check this behavior
 *
 * @param  array  $airports
 * @return string[]
 */

function getUniqueFirstLetters(array $airports): array
{
    $airportsNames = array_column($airports, 'name');
    $airportsNamesFirstLetters = array_unique(array_map('getAirportNameFirstLetter',$airportsNames));
    sort($airportsNamesFirstLetters);
    return $airportsNamesFirstLetters;
}

/**
 * @param string $airportName
 * @return string
 */
function getAirportNameFirstLetter(string $airportName): string
{
    return substr($airportName, 0, 1);
}


/**
 * @param array $airport
 * @return array|void
 */
function filterAirportsByFirstLetter(array $airport)
{
    if(getAirportNameFirstLetter($airport['name']) == $_GET['filter_by_first_letter']) {
        return $airport;
    }
}

/**
 * @param array $airport
 * @return array|void
 */
function filterAirportsByState(array $airport)
{
    if($airport['state'] === $_GET['filter_by_state']) {
        return $airport;
    }
}


/**
 * @param string $sortingParam
 * @return string
 */
function getSortingUrl(string $sortingParam): string
{
    $link =  $_SERVER['QUERY_STRING'] ? $_SERVER["REQUEST_URI"] . '&sorting=' . $sortingParam . '&order=asc':
        $_SERVER["REQUEST_URI"] . '?sorting=' . $sortingParam . '&order=asc';

    if(isset($_GET['sorting'])){
        if($_GET['order'] == 'asc'){
            $link = str_replace("asc","desc",$_SERVER["REQUEST_URI"]);
        }
        if($_GET['order'] == 'desc'){
            $link = str_replace("desc","asc",$_SERVER["REQUEST_URI"]);
        }
    }
    return $link;
}

/**
 * @param string $firstLetter
 * @return string
 */
function getFilteringByFirstLetterUrl(string $firstLetter): string
{
    return $_SERVER["PHP_SELF"] . '?filter_by_first_letter=' . $firstLetter;
}

/**
 * @param string $state
 * @return string
 */
function getFilteringByStateUrl(string $state): string
{
    return $_SERVER["PHP_SELF"] . '?filter_by_state=' . $state;
}

/**
 * @return string
 */
function resetFilters(): string
{
    return  $_SERVER["PHP_SELF"];
}

/**
 * @param int $pageNumber
 * @return string
 */
function getPagingUrl(int $pageNumber): string
{
    return $_SERVER["PHP_SELF"] . '?page=' . $pageNumber;
}

/**
 * @return bool
 */
function isFilterByLetterApplied(): bool
{
    return isset($_GET['page']);
}

/**
 * @return bool
 */
function isSortingByNameApplied(): bool
{
    return isset($_GET['sort']);
}

