<?php

namespace Apiex\Actions\Privilege;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;

trait Administration
{
    /**
     * @param Request $request
     */
    public function assign(Request $request)
    {
        $role_id = $request->get('id');
        $permission_ids = $request->get('permission_ids') ?: [$request->get('permission_id')];
        if ($role = Entities\Privilege::where('section', 'role')->where('id', $role_id)->first()) {
            $permissions = Entities\Privilege::where('section', 'permission')
                ->whereIn('id', (array) $permission_ids)
                ->get();
            foreach ($permissions as $permission) {
                Entities\PrivilegeAssignment::firstOrCreate([
                    'role_id' => $role_id,
                    'permission_id' => $permission->id,
                ]);
            }
            return app('ResponseSingular')->setItem(__('Permissions was successfully assigned.'))->send();
        }
        return app('ResponseError')->withMessage(__('role_not_found'))->send(404);
    }

    /**
     * @param Request $request
     */
    public function revoke(Request $request)
    {
        $role_id = $request->get('id');
        $permission_ids = $request->get('permission_ids') ?: [$request->get('permission_id')];
        if ($role = Entities\Privilege::where('section', 'role')->where('id', $role_id)->first()) {
            $assigneds = Entities\PrivilegeAssignment::where('role_id', $role_id)
                ->whereIn('permission_id', (array) $permission_ids)
                ->get();
            foreach ($assigneds as $assigned) {
                $assigned->delete();
            }
            return app('ResponseSingular')->setItem(__('Permissions was successfully revoked.'))->send();
        }
        return app('ResponseError')->withMessage(__('role_not_found'))->send(404);
    }
}
