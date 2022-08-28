<?php

/** @var \PDO $pdo */
require_once './pdo_ini.php';
require_once('../web/functions.php');

const AIRPORTS_TABLE_NAME = 'airports';
const CITIES_TABLE_NAME = 'cities';
const STATES_TABLE_NAME = 'states';
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

/**
 * Retrieve all airports to get all airports names' first letters
 *
 * @param PDO $pdo
 * @return array
 */

function getAllAirportsDB(\PDO $pdo): array
{
    $query = "SELECT * FROM " . AIRPORTS_TABLE_NAME;
    return fetchAllQuery($pdo,$query);
}

/**
 * Retrieve airports according to filtering and sorting conditions
 * to calculate pagination params
 *
 * @param PDO $pdo
 * @return array
 */
function getFilteredAirportsDB(\PDO $pdo): array
{
    $query = "SELECT * FROM " . AIRPORTS_TABLE_NAME . getFilteringQuery() . getSortingQuery();
    return fetchAllQuery($pdo,$query);
}

/**
 * Retrieve airports according to filtering and sorting conditions with pagination
 * to be displayed on the view page
 *
 * @param PDO $pdo
 * @return array
 */
function getDisplayedAirportsDB(\PDO $pdo): array
{
    $query = "SELECT * FROM " . AIRPORTS_TABLE_NAME . getFilteringQuery() . getSortingQuery() . getPagingQuery();
    $airports = fetchAllQuery($pdo, $query);

    foreach($airports as &$airport){
        $airport['state_name'] = getStateNameById($pdo, $airport['state_id']);
        $airport['city_name'] = getCityNameById($pdo, $airport['city_id']);
    }
    return $airports;
}

/**
 * @param PDO $pdo
 * @param int $stateId
 * @return mixed
 */
function getStateNameById(\PDO $pdo, int $stateId): mixed
{
    $query = "SELECT name FROM " . STATES_TABLE_NAME . " WHERE id = $stateId";
    $state = fetchQuery($pdo, $query);
    return $state['name'];
}

/**
 * @param PDO $pdo
 * @param int $cityId
 * @return mixed
 */
function getCityNameById(\PDO $pdo, int $cityId): mixed
{
    $query = "SELECT name FROM " . CITIES_TABLE_NAME . " WHERE id = $cityId";
    $city = fetchQuery($pdo, $query);
    return $city['name'];
}

/**
 * @return string
 */
function getFilteringQuery(): string
{
    $firstLetter = getQueryValue(FILTER_BY_FIRST_LETTER_QUERY);
    $stateId = getQueryValue(FILTER_BY_STATE_QUERY);
    $query = " WHERE name LIKE '$firstLetter%'";
    $query.= $stateId ? " AND state_id = $stateId" : "";
    return $query;
}

/**
 * @return string
 */
function getSortingQuery(): string
{
    $sortingOrder = getQueryValue(ORDER_QUERY);
    $sortingParam = getQueryValue(SORTING_QUERY);
    return match ($sortingParam) {
        'state'  => " ORDER BY (SELECT name from states where states.id = airports.state_id) $sortingOrder",
        'city' =>   " ORDER BY (SELECT name from cities where cities.id = airports.city_id) $sortingOrder",
        default =>  $sortingOrder ? " ORDER BY $sortingParam $sortingOrder" : ""
    };
}

/**
 * @return string
 */
function getPagingQuery(): string
{
    $offset = (getActivePageNumber() - 1) * PAGE_SIZE;
    return " LIMIT ". PAGE_SIZE . " OFFSET $offset";
}

/**
 * @param PDO $pdo
 * @param string $query
 * @return mixed
 */
function fetchQuery(\PDO $pdo, string $query): mixed
{
    try {
        $result = $pdo->query($query)->fetch();
    } catch (PDOException $e) {
        echo "Error in $query execution. Reason: " . $e->getMessage();
        die();
    }
    return $result;
}

/**
 * @param PDO $pdo
 * @param string $query
 * @return bool|array
 */
function fetchAllQuery(\PDO $pdo, string $query): bool|array
{
    try {
        $result = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error in $query execution. Reason: " . $e->getMessage();
        die();
    }
    return $result;
}