<?php

namespace Apiex\Actions\Menu;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;

trait ItemDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($item = Entities\MenuItem::where('id', $request->get('id'))->first()) {
            return app('ResponseSingular')->setItem($item)->send();
        }
        return app('ResponseError')->withMessage(__('menu_item_not_found'))->send(404);
    }
}
