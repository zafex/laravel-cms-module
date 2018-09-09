<?php

namespace Apiex\Actions\Menu;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Apiex\Helpers;
use Apiex\Methods\RecursiveTrait;
use Illuminate\Http\Request;

trait Navigation
{
    use RecursiveTrait {
        make as makeRecursive;
        normalize as normalizeRecursive;
    }

    /**
     * @param Request $request
     */
    function load(Request $request, Helpers\Privileges $privileges, Helpers\Settings $settings)
    {
        $id = $request->get('id') ?: $settings->fetch('navigation-menu', function ($data) {
            return $data ?: 0;
        });
        if ($menu = Entities\Menu::where('id', $id)->first()) {
            $rawItems = [];
            foreach ($menu->items ?: [] as $item) {
                if (!$item->privilege_id) {
                    $rawItems[] = $item->toArray();
                } elseif ($privilege = $privileges->fetch($item->privilege_id)) {
                    if ($privileges->hasAccess($privilege['name'], $privilege['section'])) {
                        $rawItems[] = $item->toArray();
                    }
                }
            }
            $normalizeRecursive = $this->normalizeRecursive($rawItems);
            $itemRecursive = $this->makeRecursive($normalizeRecursive);
            return app('ResponseSingular')->setItem([
                'menu' => [
                    'id' => $menu->id,
                    'label' => $menu->label,
                    'description' => $menu->description,
                ],
                'rawItems' => $rawItems,
                'recursiveItems' => $itemRecursive,
            ])->send();
        }
        return app('ResponseError')->withMessage(__('menu_not_found'))->send(404);
    }
}
