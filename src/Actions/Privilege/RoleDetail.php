<?php

namespace Apiex\Actions\Privilege;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;

trait RoleDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($role = Entities\Privilege::where('section', 'role')->where('id', $request->get('id'))->first()) {
            $role->load('childRelations');
            return app('ResponseSingular')->setItem($role)->send();
        }
        return app('ResponseError')->withMessage(__('role_not_found'))->send(404);
    }
}
