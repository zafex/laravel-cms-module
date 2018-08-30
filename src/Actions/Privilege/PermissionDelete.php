<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;

trait PermissionDelete
{
    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        try {
            $permission_id = $request->get('id');
            $permission = Entities\Privilege::where('id', $permission_id)
                ->where('section', 'permission')
                ->first();
            if (!$permission) {
                return app('ResponseError')->withMessage('permission_not_found')->send(404);
            }
            $permission->delete();

            return app('ResponseSingular')->setItem(__('Permissions was successfully deleted.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
