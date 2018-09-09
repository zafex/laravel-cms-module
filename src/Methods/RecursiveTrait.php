<?php

namespace Apiex\Methods;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

trait RecursiveTrait
{
    /**
     * @param  array       $data
     * @param  $parentId
     * @return mixed
     */
    public function make(array $data, $parentId = 0): array
    {
        if (!array_key_exists($parentId, $data)) {
            return [];
        }
        $items = [];
        foreach ($data[$parentId] as $item) {
            $itemToAppend = $item;
            if (array_key_exists($item['id'], $data)) {
                $itemToAppend['childs'] = $this->make($data, $item['id']);
            }
            $items[] = $itemToAppend;
        }
        return $items;
    }

    /**
     * @param array        $data
     * @param $parentId
     * @param $parentKey
     */
    public function normalize(array $data, $parentKey = 'parent_id'): array
    {
        $items = [];
        foreach ($data ?: [] as $item) {
            $parentId = array_key_exists($parentKey, $item) ? $item[$parentKey] : 0;
            $items[$parentId][] = $item;
        }
        return $items;
    }
}
