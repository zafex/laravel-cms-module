<?php

namespace Apiex\Actions\Privilege;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;

trait PermissionDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($permission = Entities\Privilege::where('section', 'permission')->where('id', $request->get('id'))->first()) {
            return app('ResponseSingular')->setItem($permission)->send();
        }
        return app('ResponseError')->withMessage(__('permission_not_found'))->send(404);
    }
}
