<?php

namespace Apiex\Actions\User;

use Apiex\Entities;
use Illuminate\Http\Request;

trait Administration
{
    /**
     * @param Request $request
     */
    public function assign(Request $request)
    {
        $user_id = $request->get('id');
        $role_ids = $request->get('role_ids') ?: [$request->get('role_id')];
        if ($user = Entities\User::where('id', $user_id)->first()) {
            $roles = Entities\Privilege::where('section', 'role')
                ->whereIn('id', (array) $role_ids)
                ->get();
            foreach ($roles as $role) {
                Entities\RoleUser::firstOrCreate([
                    'user_id' => $user_id,
                    'role_id' => $role->id,
                ]);
            }
            return app('ResponseSingular')->setItem(__('Roles was successfully assigned.'))->send();
        }
        return app('ResponseError')->withMessage(__('user_not_found'))->send(404);
    }

    /**
     * @param Request $request
     */
    public function revoke(Request $request)
    {
        $user_id = $request->get('id');
        $role_ids = $request->get('role_ids') ?: [$request->get('role_id')];
        if ($user = Entities\User::where('id', $user_id)->first()) {
            $assigneds = Entities\RoleUser::where('user_id', $user_id)
                ->whereIn('role_id', (array) $role_ids)
                ->get();
            foreach ($assigneds as $assigned) {
                $assigned->delete();
            }
            return app('ResponseSingular')->setItem(__('Roles was successfully revoked.'))->send();
        }
        return app('ResponseError')->withMessage(__('user_not_found'))->send(404);
    }
}
