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
use Exception;
use Illuminate\Http\Request;

trait ItemDelete
{
    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        try {
            $item_id = $request->get('id');
            $item = Entities\MenuItem::where('id', $item_id)->first();
            if (!$item) {
                return app('ResponseError')->withMessage('menu_item_not_found')->send(404);
            }
            $item->delete();

            return app('ResponseSingular')->setItem(__('Menu Item was successfully deleted.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
