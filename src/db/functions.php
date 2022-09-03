<?php

// implements DRY principle
require_once('../web/functions.php');

const PATH_TO_LOG_FILE = './log.txt';
const LOGGING_TIME_FORMAT = 'Y/m/d h:i:s';
const SORTING_PARAMS_NAMES = ['name' => 'Name','code' => 'Code','state_name' => 'State','city_name' => 'City'];
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
    $bindParamNames = ['firstLetter','stateName'];
    return customFetchAll($pdo, $query, $bindParamNames);
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
    $query = getAllRecordsSqlQuery() . getFilteringSqlQuery() . getSortingSqlQuery() . " LIMIT :limit OFFSET :offset";
    $bindParamNames = ['firstLetter', 'stateName', 'limit', 'offset'];
    return customFetchAll($pdo, $query, $bindParamNames);
}

/**
 * @return string
 */
function getAllRecordsSqlQuery(): string
{
    $sqlQuery = <<<'SQL'
    SELECT airports.id, airports.name, airports.code, airports.address, airports.timezone,
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
    $sortingParam = in_array(getQueryValue(SORTING_QUERY), array_keys(SORTING_PARAMS_NAMES))
        ? getQueryValue(SORTING_QUERY)
        : DEFAULT_SORTING_PARAM;
    $sortingOrder = in_array(getQueryValue(ORDER_QUERY), ['asc','desc'])
        ? getQueryValue(ORDER_QUERY)
        : DEFAULT_SORTING_ORDER;
    return  $sortingParam ? " ORDER BY $sortingParam $sortingOrder " : "";
}

/**
 * @param PDO $pdo
 * @param string $query
 * @param array $bindParams
 * @return bool|array
 */
function customFetchAll(\PDO $pdo, string $query, array $bindParams = []): bool|array
{
    try {
        $sth = $pdo->prepare($query);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        foreach($bindParams as $bindParam){
            customBindParam($sth, $bindParam);
        }
        $sth->execute();
        $result = $sth->fetchAll();
    } catch (PDOException $e) {
        writeToLog("Error in $query execution. Reason: " . $e->getMessage());
        die();
    }
    return $result;
}

/**
 * @param PDOStatement $sth
 * @param string $bindParamName
 * @return bool
 */
function customBindParam(\PDOStatement $sth, string $bindParamName): bool
{
    $bindParamArgsNames = ['paramName','paramValue','paramType'];
    $bindParamValues = [
        'firstLetter' => ['firstLetter', getQueryValue(FILTER_BY_FIRST_LETTER_QUERY) . "%", \PDO::PARAM_STR],
        'stateName' => ['stateName', getQueryValue(FILTER_BY_STATE_QUERY) ?: "%", \PDO::PARAM_STR],
        'offset' => ['offset', (getActivePageNumber() - 1) * PAGE_SIZE, \PDO::PARAM_INT],
        'limit' => ['limit', PAGE_SIZE, \PDO::PARAM_INT],
    ];
    extract(array_combine($bindParamArgsNames, $bindParamValues[$bindParamName]));
    return $sth->bindParam($paramName, $paramValue, $paramType);
}

/**
 * @param $message
 * @return void
 */
function writeToLog(string $message): void
{
    $file = PATH_TO_LOG_FILE;
    $current = file_get_contents($file);
    $current .= '['. date(LOGGING_TIME_FORMAT ).']' . $message . PHP_EOL;
    file_put_contents($file, $current);
}
