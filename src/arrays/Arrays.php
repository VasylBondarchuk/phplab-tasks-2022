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
            $result = array_merge($result, array_fill(0, $el, $el));
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
        foreach($input as $row) {
            foreach ($row[self::TAGS_KEY] as $tag) {
                $groupByTag[$tag][] = $row[self::NAME_KEY];
                sort($groupByTag[$tag]);
            }
        }
        ksort($groupByTag);
        return $groupByTag;
    }
}