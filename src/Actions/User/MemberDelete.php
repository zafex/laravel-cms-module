<?php

namespace Apiex\Actions\User;

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
            return app('ResponseError')->sendMessage('Cannot delete your self', 403);
        } elseif ($user = Entities\User::where('id', $user_id)->first()) {
            $user->status = 0;
            $user->save();
            return app('ResponseSingular')->send('User was successfully deleted.');
        }
        return app('ResponseError')->sendMessage('User not found', 404);
    }
}
