<?php

/** @var \PDO $pdo */
require_once './pdo_ini.php';
require_once('../web/functions.php');
require_once('../web/airports.php');
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
 * @param PDO $pdo
 * @return array
 */

function getAllAirportsDB(\PDO $pdo): array
{
    $airports = [];
    try {
        $query = "SELECT * FROM airports";
        $airports = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }
    return $airports;
}

function getFilteredAndSortedAirports(\PDO $pdo): array
{
    $airports = [];
    try {
        $query = "SELECT * FROM airports" . getFilteringQuery() . getSortingQuery();
        $airports = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }
    return $airports;
}

function getFilteringQuery(): string
{
    $firstLetter = getFilteringParamValue(FILTER_BY_FIRST_LETTER_QUERY);
    $stateId = getFilteringParamValue(FILTER_BY_STATE_QUERY);
    $query = " WHERE name LIKE '$firstLetter%'";
    $query.= $stateId ? " AND state_id = $stateId" : "";
    return $query;
}

function getSortingQuery(): string
{
    $sortingOrder = getSortingOrder();
    $sortingParam = getSortingParam();
    return match ($sortingParam) {
        'state'  => " ORDER BY (SELECT name from states where states.id = airports.state_id) $sortingOrder",
        'city' =>   " ORDER BY (SELECT name from cities where cities.id = airports.city_id) $sortingOrder",
        default =>  $sortingOrder ? " ORDER BY $sortingParam $sortingOrder" : ""
    };
}

function displayAirports(\PDO $pdo): array
{
    $airports = getFilteredAndSortedAirports($pdo);
    foreach($airports as &$airport){
        $airport['state_name'] = getStateNameById($pdo, $airport['state_id']);
        $airport['city_name'] = getCityNameById($pdo, $airport['city_id']);
    }
    return $airports;
}

function getStateNameById(\PDO $pdo, int $stateId)
{
    try {
        $query = "SELECT name FROM states WHERE id = $stateId";
        $state = $pdo->query($query)->fetch();
    } catch (PDOException $e) {
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }
    return $state['name'];
}

function getCityNameById(\PDO $pdo, int $cityId)
{
    try {
        $query = "SELECT name FROM cities WHERE id = $cityId";
        $city =  $pdo->query($query)->fetch();
    } catch (PDOException $e) {
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }
    return $city['name'];
}
