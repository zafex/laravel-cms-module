<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Illuminate\Http\Request;

trait PermissionDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($permission = Entities\Privilege::where('section', 'permission')->where('id', $request->get('id'))->first()) {
            return app('ResponseSingular')->send($permission);
        }
        return app('ResponseError')->sendMessage('Role not found', 404);
    }
}
