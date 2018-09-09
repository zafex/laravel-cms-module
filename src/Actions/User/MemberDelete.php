<?php

namespace Apiex\Actions\User;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;

trait MemberDelete
{
    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $user_id = $request->get('id');
        if ($user_id == auth()->user()->id) {
            return app('ResponseError')->withMessage(__('Cannot delete your self'))->send(403);
        } elseif ($user = Entities\User::where('id', $user_id)->first()) {
            $user->status = 0;
            $user->save();
            return app('ResponseSingular')->setItem(__('User was successfully deleted.'))->send();
        }
        return app('ResponseError')->withMessage(__('user_not_found'))->send(404);
    }
}
