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

const PAGE_SIZE = 5;
const PAGE_QTY_AROUND_ACTIVE = 5;
const DEFAULT_SORTING_ORDER = 'asc';
const FILTER_BY_STATE_QUERY = 'filter_by_state';
const FILTER_BY_FIRST_LETTER_QUERY = 'filter_by_first_letter';
const SORTING_QUERY = 'sorting';
const ORDER_QUERY = 'order';
const PAGE_QUERY = 'page';

/**
 * @param array $airports
 * @return array
 */
function getUniqueFirstLetters(array $airports): array
{
    $airportsNames = array_column($airports, 'name');
    $airportsNamesFirstLetters = array_unique(array_map('getAirportNameFirstLetter',$airportsNames));
    sort($airportsNamesFirstLetters);
    return $airportsNamesFirstLetters;
}

// FILTERING


/**
 * @param $airports
 * @return array|mixed
 */
function filterByFirstLetter($airports): mixed
{
    if(isFilteringApplied(FILTER_BY_FIRST_LETTER_QUERY)){
        $airports =  array_filter($airports, function($airport){ if(getAirportNameFirstLetter($airport['name'])
            == getQueryValue(FILTER_BY_FIRST_LETTER_QUERY)) { return $airport; }
        });
    }
    return $airports;
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
 * @param $airports
 * @return array|mixed
 */
function filterByState($airports): mixed
{
    if(isFilteringApplied(FILTER_BY_STATE_QUERY)){
        $airports =  array_filter($airports,function($airport){if($airport['state']
            === getQueryValue(FILTER_BY_STATE_QUERY)){ return $airport; }
        });
    }
    return $airports;
}

/**
 * @param array $airports
 * @return array
 */
function filterByPage(array $airports): array
{
    $airports = array_values($airports);
    return array_filter($airports, function($airport) use ($airports) {
        $airportIndex = array_search($airport, $airports);
        if($airportIndex >= (getActivePageNumber() - 1) * PAGE_SIZE
            && $airportIndex < getActivePageNumber() * PAGE_SIZE) { return $airport;}
    });
}

/**
 * @param string $filteringParam
 * @return bool
 */
function isFilteringApplied(string $filteringParam) : bool
{
    return isset($_GET[$filteringParam]);
}

// SORTING

/**
 * @param array $airports
 * @return array
 */
function sortingAirports(array $airports) : array
{
    $order = getQueryValue(ORDER_QUERY) == 'desc' ? SORT_DESC : SORT_ASC;
    array_multisort(array_column($airports, 'name'), $order, $airports);
    return $airports;
}

/**
 * @param string $filteringParam
 * @param string $filteringVal
 * @return string
 */
function setFilteringUrl(string $filteringParam, string $filteringVal = null): string
{
    $data = [$filteringParam => $filteringVal];
    return setUrl($data);
}

/**
 * @param int $pageNumber
 * @return string
 */
function setPageNumberUrl(int $pageNumber): string
{
    $data = [PAGE_QUERY => $pageNumber];
    $url = setUrl($data);
    if(isFilteringApplied(PAGE_QUERY)){
        $url = str_replace(PAGE_QUERY . '=' . getQueryValue(PAGE_QUERY),PAGE_QUERY
            . '=' . $pageNumber, $_SERVER['REQUEST_URI']);
    }
    return $url;
}

/**
 * @param $data
 * @return string
 */
function setUrl($data): string
{
    $pageQuery = [PAGE_QUERY => 1];
    $data = array_merge($pageQuery, $data);
    return $_SERVER['PHP_SELF'] . '?'. http_build_query($data);
}

/**
 * @param string $sortingParam
 * @return string
 */
function setSortingUrl(string $sortingParam): string
{
    $data = [SORTING_QUERY => $sortingParam, ORDER_QUERY => DEFAULT_SORTING_ORDER];
    $queryChar = $_SERVER['QUERY_STRING'] ? '&' : '?';
    $sortingUrl = $_SERVER['REQUEST_URI']. $queryChar . http_build_query($data);
    if(isset($_GET[SORTING_QUERY])){
        $sortingUrl = getQueryValue(SORTING_QUERY) == $sortingParam ? switchSortingOrder() : switchSortingParameter($sortingParam);
    }
    return $sortingUrl;
}

/**
 * @return string
 */
function switchSortingOrder(): string
{
    $switchedOrder = getQueryValue(ORDER_QUERY) == 'asc' ? 'desc' : 'asc';
    return str_replace(getQueryValue(ORDER_QUERY), $switchedOrder, $_SERVER["REQUEST_URI"]);
}

/**
 * @param string $sortingParam
 * @return string
 */
function switchSortingParameter(string $sortingParam): string
{
    return str_replace(getQueryValue(SORTING_QUERY), $sortingParam, $_SERVER["REQUEST_URI"]);
}

/**
 * @param string $query
 * @return string
 */
function getQueryValue(string $query) : string
{
    return $_GET[$query] ?? '';
}

// PAGINATION

/**
 * @param int $pageNum
 * @return string
 */
function getPageNumberLinkClass(int $pageNum):string
{
    return $pageNum === getActivePageNumber() ? "page-item active" : "page-item";
}

/**
 * @return int
 */
function getActivePageNumber(): int
{
    $pageNumber = 1;
    if(isFilteringApplied(PAGE_QUERY)){
        $pageNumber = $_GET[PAGE_QUERY];
    }
    return (int)$pageNumber;
}

/**
 * @return int
 */
function getFirstDisplayedPage(): int
{
    return (getActivePageNumber() - PAGE_QTY_AROUND_ACTIVE < 1) ? 1 : getActivePageNumber() - PAGE_QTY_AROUND_ACTIVE;
}

/**
 * @param $filteredAirports
 * @return int
 */
function getLastDisplayedPage($filteredAirports): int
{
    return (getActivePageNumber() + PAGE_QTY_AROUND_ACTIVE) > getDisplayedPagesQty($filteredAirports)
        ? getDisplayedPagesQty($filteredAirports)
        : getActivePageNumber() + PAGE_QTY_AROUND_ACTIVE;
}

/**
 * @param array $filteredAirports
 * @return int
 */
function getDisplayedPagesQty(array $filteredAirports): int
{
    return (int)(ceil(count($filteredAirports) / PAGE_SIZE));
}

function getNextPage($filteredAirports): int
{
    return getActivePageNumber() + 1 <= getLastDisplayedPage($filteredAirports)
        ? getActivePageNumber() + 1
        : getLastDisplayedPage($filteredAirports);
}

function getPrevPage(): int
{
    return getActivePageNumber() - 1 >= getFirstDisplayedPage()
        ? getActivePageNumber() - 1
        : getFirstDisplayedPage();
}

/**
 * Retrieve all airports to get all airports names' first letters
 *
 * @return mixed
 */
function getAllAirports() : array
{
    return require './airports.php';
}

/**
 * Retrieve airports according to filtering and sorting conditions
 * to calculate pagination params
 *
 * @return array
 */
function getFilteredAirports(): array
{
    return sortingAirports(filterByState(filterByFirstLetter(getAllAirports())));
}

/**
 * Retrieve airports according to filtering and sorting conditions with pagination
 * to be displayed on the view page
 *
 * @return array
 */
function getDisplayedAirports(): array
{
    return filterByPage(getFilteredAirports());
}