<?php

namespace arrays;

/**
 *
 */
class Arrays implements ArraysInterface
{

    //sub array keys' names
    const NAME_KEY = 'name';
    const TAGS_KEY = 'tags';

    /**
     * @param array $input
     * @return array
     */
    public function repeatArrayValues(array $input): array
    {
        $result = [];
        foreach($input as $el)
        {
            for($i = 0; $i < abs($el); $i++)
            {
                $result[] = $el;
            }
        }
        return $result;
    }

    /**
     * @param array $input
     * @return int
     */
    public function getUniqueValue(array $input): int
    {
        $unique = [];
        foreach(array_count_values($input) as $el => $freq)
        {
            if($freq === 1){
                $unique[] =  $el;
            }
        }
        return $unique ? min($unique) : 0;
    }

    /**
     * @param array $input
     * @return array
     */
    public function groupByTag(array $input): array
    {
        $groupByTag = [];
        foreach($this->getSortedTagNamesList($input) as $tagName)
        {
            foreach($input as $row){
                if (in_array($tagName, $row[self::TAGS_KEY])){
                    $groupByTag[$tagName][] = $row[self::NAME_KEY];
                    sort($groupByTag[$tagName]);
                }
            }
        }
        return $groupByTag;
    }

    /**
     * @param array $input
     * @return array
     */
    public function getSortedTagNamesList(array $input): array
    {
        $tagsList = [];
        foreach(array_column($input, self::TAGS_KEY) as $tags)
        {
            $tagsList = array_merge($tagsList, $tags);
        }
        $tagsList = array_unique($tagsList);
        sort($tagsList);
        return $tagsList;
    }
}