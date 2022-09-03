<?php

// implements DRY principle
require_once('../web/functions.php');

const PATH_TO_LOG_FILE = './log.txt';
const LOGGING_TIME_FORMAT = 'Y/m/d h:i:s';
const SORTING_PARAMS_NAMES = ['name' => 'Name', 'code' => 'Code', 'state_name' => 'State', 'city_name' => 'City'];
const DEFAULT_SORTING_PARAM = 'name';

/**
 * Retrieve all airports to get all airports names' first letters
 *
 * @param PDO $pdo
 * @return array
 */

function getAllAirportsDB(\PDO $pdo): array
{
    $query = getAllRecordsSqlQuery();
    return customFetchAll($pdo, $query);
}

/**
 * Retrieve airports according to filtering conditions
 * to calculate pagination params
 *
 * @param PDO $pdo
 * @return array
 */
function getFilteredAirportsDB(\PDO $pdo): array
{
    $query = getAllRecordsSqlQuery() . getFilteringSqlQuery();
    return customFetchAll($pdo, $query, getBindParamValues());
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
    $sqlQuery = getAllRecordsSqlQuery() . getFilteringSqlQuery() . getSortingSqlQuery() . getPaginationSqlQuery();
    return customFetchAll($pdo, $sqlQuery, getBindParamValues());
}

/**
 * @return string
 */
function getAllRecordsSqlQuery(): string
{
    $sqlQuery = <<<'SQL'
    SELECT DISTINCT airports.id, airports.name, airports.code, airports.address, airports.timezone,
    cities.name AS city_name, states.name AS state_name FROM airports INNER JOIN cities
    ON airports.city_id = cities.id INNER JOIN states ON airports.state_id = states.id
    SQL;
    return $sqlQuery;
}
/**
 * @return string
 */
function getFilteringSqlQuery(): string
{
    return " WHERE airports.name LIKE :firstLetter AND states.name LIKE :stateName";
}

/**
 * @return string
 */
function getSortingSqlQuery(): string
{
    // Validate sorting params, obtained from URL to avoid any injections
    $sortingParam = in_array(getQueryValue(SORTING_QUERY), array_keys(SORTING_PARAMS_NAMES))
        ? getQueryValue(SORTING_QUERY)
        : DEFAULT_SORTING_PARAM;
    $sortingOrder = in_array(getQueryValue(ORDER_QUERY), ['asc','desc'])
        ? getQueryValue(ORDER_QUERY)
        : DEFAULT_SORTING_ORDER;
    return  isFilteringApplied(ORDER_QUERY) ? " ORDER BY $sortingParam $sortingOrder " : "";
}

/**
 * Pagination parameters are always integers, so we can use them in sql query as is
 *
 * @return string
 */
function getPaginationSqlQuery() : string
{
    $offset = (getActivePageNumber() - 1) * PAGE_SIZE;
    $limit = PAGE_SIZE;
    return isFilteringApplied(PAGE_QUERY) ? " LIMIT $limit OFFSET $offset" : "";
}

/**
 * @param PDO $pdo
 * @param string $query
 * @param array $bindParamsValues
 * @return bool|array
 */
function customFetchAll(\PDO $pdo, string $query, array $bindParamsValues = []): bool|array
{
    try {
        $sth = $pdo->prepare($query);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $sth->execute($bindParamsValues);
        $result = $sth->fetchAll();
    } catch (PDOException $e) {
        writeToLog("Error in $query execution. Reason: " . $e->getMessage());
        die();
    }
    return $result;
}

/**
 * @return array
 */
function getBindParamValues(): array
{
    return [
        'firstLetter' => getQueryValue(FILTER_BY_FIRST_LETTER_QUERY) . "%",
        'stateName' => getQueryValue(FILTER_BY_STATE_QUERY) ?: "%"
    ];
}

/**
 * @param string $message
 * @return void
 */
function writeToLog(string $message): void
{
    $file = PATH_TO_LOG_FILE;
    $loggingTime = date(LOGGING_TIME_FORMAT);
    $record = "[$loggingTime] " . $message . PHP_EOL;
    if(!is_file($file)){
        file_put_contents($file, $record);
    }
    $content = file_get_contents($file);
    $content .= $record;
    file_put_contents($file, $content);
}
