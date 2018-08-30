<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;

trait RoleDelete
{
    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        try {
            $role_id = $request->get('id');
            $role = Entities\Privilege::where('id', $role_id)
                ->where('section', 'role')
                ->first();
            if (!$role) {
                return app('ResponseError')->withMessage('role_not_found')->send(404);
            }
            $role->delete();

            return app('ResponseSingular')->setItem(__('Permissions was successfully deleted.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
