<?php

namespace Apiex\Actions\User;

use Apiex\Entities\User;
use Illuminate\Http\Request;

trait MemberDelete
{
    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        ;
        if ($user = User::where('id', $request->get('id'))->first()) {
            $user->status = 0;
            $user->save();
            return app('ResponseSingular')->send('User was successfully deleted.');
        }
        return app('ResponseError')->sendMessage('User not found', 404);
    }
}
