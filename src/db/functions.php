<?php

/** @var \PDO $pdo */
require_once './pdo_ini.php';
require_once('../web/functions.php');

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
    $query = getAllRecordsQuery();
    return customFetchAll($pdo, $query);
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
    $query = getAllRecordsQuery() . getFilteringQuery();
    return customFetchAll($pdo, $query,['firstLetter','stateName']);
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
    $query = getAllRecordsQuery() . getFilteringQuery() . getSortingQuery() .  getPagingQuery();
    return customFetchAll($pdo, $query,['limit','offset','firstLetter','stateName']);
}

/**
 * @return string
 */
function getAllRecordsQuery(): string
{
    $sql = <<<'SQL'
    SELECT airports.id, airports.name, airports.code, airports.address, airports.timezone,
    cities.name AS city_name, states.name AS state_name FROM airports INNER JOIN cities
    ON airports.city_id = cities.id INNER JOIN states ON airports.state_id = states.id
    SQL;
    return $sql;
}
/**
 * @return string
 */
function getFilteringQuery(): string
{
    $query = " WHERE airports.name LIKE :firstLetter AND states.name LIKE :stateName";
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
        'state' =>   " ORDER BY state_name $sortingOrder",
        'city'  =>   " ORDER BY city_name $sortingOrder",
        default =>   $sortingOrder ? " ORDER BY $sortingParam $sortingOrder" : ""
    };
}

/**
 * @return string
 */
function getPagingQuery(): string
{
    return " LIMIT :limit OFFSET :offset";
}

/**
 * @param PDO $pdo
 * @param string $query
 * @param array $params
 * @return bool|array
 */
function customFetchAll(\PDO $pdo, string $query, array $params = []): bool|array
{
    try {
        $sth = $pdo->prepare($query);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        foreach($params as $param){
            customBindParam($sth, $param);
        }
        $sth->execute();
        $result = $sth->fetchAll();
    } catch (PDOException $e) {
        echo "Error in $query execution. Reason: " . $e->getMessage();
        die();
    }
    return $result;
}

/**
 * @param $sth
 * @param string $bindParam
 * @return mixed
 */
function customBindParam($sth, string $bindParam): mixed
{
    $offset = (getActivePageNumber() - 1) * PAGE_SIZE;
    $limit = PAGE_SIZE;
    $stateName = getQueryValue(FILTER_BY_STATE_QUERY) ?: "%";
    $firstLetter = getQueryValue(FILTER_BY_FIRST_LETTER_QUERY) . "%";
    $paraArray = [
        'limit' => ['param' => 'limit', 'var'  => $limit, 'type' => \PDO::PARAM_INT],
        'offset' => ['param' => 'offset', 'var'  => $offset, 'type' => \PDO::PARAM_INT],
        'firstLetter' => ['param' => 'firstLetter', 'var'  => $firstLetter, 'type' => \PDO::PARAM_STR],
        'stateName' => ['param' => 'stateName', 'var'  => $stateName, 'type' => \PDO::PARAM_STR]
    ];
    extract($paraArray[$bindParam]);
    return $sth->bindParam($param, $var, $type);
}