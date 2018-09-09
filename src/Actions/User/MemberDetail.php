<?php

namespace Apiex\Actions\User;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;

trait MemberDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($user = Entities\User::where('id', $request->get('id'))->first()) {
            $user->load(['details', 'roles']);
            return app('ResponseSingular')->setItem($user)->send();
        }
        return app('ResponseError')->withMessage(__('user_not_found'))->send(404);
    }
}
