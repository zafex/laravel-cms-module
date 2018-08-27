<?php

namespace Apiex\Actions\User;

use Apiex\Entities\User;
use Illuminate\Http\Request;

trait MemberDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($user = User::where('id', $request->get('id'))->first()) {
            $user->load(['details', 'roles']);
            return app('ResponseSingular')->send($user);
        }
        return app('ResponseError')->sendMessage('User not found', 404);
    }
}
