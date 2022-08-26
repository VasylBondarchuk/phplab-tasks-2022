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
const PAGE_QTY_AROUND_ACTIVE = 10;
const DEFAULT_SORTING_ORDER = 'asc';
const DEFAULT_SORTING_PARAM = 'name';
const DEFAULT_PAGE_NUMBER = 1;
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
function filterByFirstLetter($airports): mixed
{
    if(isFilteringByFirstLetterApplied()){
        $airports =  array_filter($airports, function($airport){ if(getAirportNameFirstLetter($airport['name'])
            == getFilteringParamValue(FILTER_BY_FIRST_LETTER_QUERY)) { return $airport; }
        });
    }
    return $airports;
}

/**
 * @param $airports
 * @return array|mixed
 */
function filterByState($airports): mixed
{
    if(isFilteringByStateApplied()){
        $airports =  array_filter($airports,function($airport){if($airport['state'] === $_GET[FILTER_BY_STATE_QUERY]){
            return $airport;
        }
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
    if(isPaginationApplied()){
        $airports = array_values($airports);
        $airports =  array_filter($airports, function($airport) use ($airports) {
            if(shouldBeDisplayedOnActivePage($airport, $airports)) { return $airport; }
        });
    }
    return $airports;
}

/**
 * @return bool
 */
function isFilteringByStateApplied() : bool
{
    return isset($_GET[FILTER_BY_STATE_QUERY]);
}

/**
 * @return bool
 */
function isFilteringByFirstLetterApplied() : bool
{
    return isset($_GET[FILTER_BY_FIRST_LETTER_QUERY]);
}

/**
 * @param array $airports
 * @return array
 */
function sortingAirports(array $airports) : array
{
    if(isSortingApplied()){
        usort($airports,"customAirportSorting");
    }
    return $airports;
}

/**
 * @param array $airport1
 * @param array $airport2
 * @return int
 */
function customAirportSorting(array $airport1, array $airport2): int
{
    if ($airport1[getSortingParam()] == $airport2[getSortingParam()]){
        return 0;
    }
    return ($airport1[getSortingParam()] < $airport2[getSortingParam()]) ? -1 * getOrderCode() : getOrderCode();
}

/**
 * @return string
 */
function getSortingParam(): string
{
    return isset($_GET[SORTING_QUERY]) ? filter_var($_GET[SORTING_QUERY],FILTER_SANITIZE_URL)
        : DEFAULT_SORTING_PARAM;
}

/**
 * @return int
 */
function getOrderCode(): int
{
    return $_GET[ORDER_QUERY] == 'asc' ? 1 : -1;
}

function getUrl($data): string
{
    $pageNumber = isPaginationApplied() ? null : DEFAULT_PAGE_NUMBER;
    $pageQuery = [PAGE_QUERY => $pageNumber];
    $data = array_merge($pageQuery,$data);
    return $_SERVER["REQUEST_URI"] . getQuerySymbol(). http_build_query($data);
}

/**
 * @param string $sortingParam
 * @return string
 */
function getSortingUrl(string $sortingParam): string
{
    $data = [SORTING_QUERY => $sortingParam, ORDER_QUERY =>  DEFAULT_SORTING_ORDER];
    $url = getUrl($data);
    if(isSortingApplied()){
        $url = getSortingParam() == $sortingParam ? switchSortingOrder() : switchSortingParameter($sortingParam);
    }
    return $url;
}

/**
 * @return string
 */
function switchSortingOrder(): string
{
    return str_replace(getAppliedOrderCode(), switchOrderCode(), $_SERVER["REQUEST_URI"]);
}

/**
 * @param string $sortingParam
 * @return string
 */
function switchSortingParameter(string $sortingParam): string
{
    return str_replace(getSortingParam(), $sortingParam, $_SERVER["REQUEST_URI"]);
}

/**
 * @return mixed|string
 */
function getAppliedOrderCode(): mixed
{
    return $_GET[ORDER_QUERY] ?: DEFAULT_SORTING_ORDER;
}

/**
 * @return string
 */
function switchOrderCode(): string
{
    return isSortingApplied() && getAppliedOrderCode() == 'asc' ? 'desc' : 'asc';
}

/**
 * @return bool
 */
function isSortingApplied(): bool
{
    return (isset($_GET[SORTING_QUERY]) && isset($_GET[ORDER_QUERY]));
}

/**
 * @return bool
 */
function isAnyRequestApplied(): bool
{
    return (bool)$_SERVER['QUERY_STRING'];
}

/**
 * @param string $firstLetter
 * @return string
 */
function getFilteringByFirstLetterUrl(string $firstLetter): string
{
    $data = [FILTER_BY_FIRST_LETTER_QUERY =>  $firstLetter];
    $url = getUrl($data);
    if(isFilteringByFirstLetterApplied()){
        $url = updateFilteringParam($firstLetter,FILTER_BY_FIRST_LETTER_QUERY);
    }
    return $url;
}

/**
 * @param string $state
 * @return string
 */
function getFilteringByStateUrl(string $state): string
{
    $data = [FILTER_BY_STATE_QUERY =>  $state];
    $url = getUrl($data);
    if(isFilteringByStateApplied()){
        $url = updateFilteringParam($state,FILTER_BY_STATE_QUERY);
    }
    return $url;
}

/**
 * @param int $pageNumber
 * @return string
 */
function getPageNumberUrl(int $pageNumber): string
{

    $data = [PAGE_QUERY =>  $pageNumber];
    $url = getUrl($data);
    if(isPaginationApplied()){
        $url = updateFilteringParam($pageNumber, PAGE_QUERY);
    }
    return $url;
}

/**
 * @param string $param
 * @param string $queryName
 * @return string
 */
function updateFilteringParam(string $param, string $queryName): string
{
    return str_replace($queryName . '=' . getFilteringParamValue($queryName),$queryName . '=' . $param,
        $_SERVER['REQUEST_URI']);
}

/**
 * @param string $filteringParam
 * @return string
 */
function getFilteringParamValue(string $filteringParam) : string
{
    return isset($_GET[$filteringParam]) ?  filter_var($_GET[$filteringParam], FILTER_SANITIZE_URL) :  '';
}

/**
 * @return string
 */
function getQuerySymbol(): string
{
    return isAnyRequestApplied()  ?  '&' : '?' ;
}

/**
 * @param array $airport
 * @param array $airports
 * @return bool
 */
function shouldBeDisplayedOnActivePage(array $airport, array $airports): bool
{
    $airportIndex = array_search($airport, $airports);
    return floor($airportIndex / PAGE_SIZE) == getActivePageNumber($airports) - 1 ;
}

/**
 * @param int $pageNum
 * @return string
 */
function getPageNumberLinkClass(int $pageNum, array $airports):string
{
    return $pageNum === getActivePageNumber($airports) ? "page-item active" : "page-item";
}

/**
 * @return bool
 */
function isPaginationApplied(): bool
{
    return isset($_GET[PAGE_QUERY]);
}

/**
 * @return int
 */
function getActivePageNumber($airports): int
{
    $pageNumber = DEFAULT_PAGE_NUMBER;
    if(isPaginationApplied() && isPageNumberValid($airports)){
        $pageNumber = filter_var($_GET[PAGE_QUERY], FILTER_SANITIZE_URL);
    }
    return $pageNumber;
}

/**
 * @return bool
 */
function isPageNumberValid($airports): bool
{
    return in_array($_GET[PAGE_QUERY], range(1, getPagesQty($airports)));
}

/**
 * @return int
 */
function getFirstDisplayedPage($airports): int
{
    return (getActivePageNumber($airports) - PAGE_QTY_AROUND_ACTIVE) < 1
        ? 1
        : getActivePageNumber($airports) - PAGE_QTY_AROUND_ACTIVE;
}

/**
 * @return int
 */
function getLastDisplayedPage($airports): int
{
    return (getActivePageNumber($airports) + PAGE_QTY_AROUND_ACTIVE) > getPagesQty($airports)  ? getPagesQty($airports)
        : getActivePageNumber($airports) + PAGE_QTY_AROUND_ACTIVE;
}

/**
 * @return int
 */
function getPagesQty($airports): int
{
    $filteredAirportsQty = count(filterByState(filterByFirstLetter($airports)));
    return (int)(ceil($filteredAirportsQty / PAGE_SIZE));
}

/**
 * @return array
 */
function getDisplayedPagesRange($airports): array
{
    return range(getFirstDisplayedPage($airports), getLastDisplayedPage($airports));
}

/**
 * @return string
 */
function resetAllFilters(): string
{
    return $_SERVER['PHP_SELF'];
}

function getSortingOrder()
{
    return $_GET[ORDER_QUERY] ?? "";
}

function getAllAirports()
{
    return require './airports.php';
}