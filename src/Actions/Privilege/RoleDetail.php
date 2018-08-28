<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Illuminate\Http\Request;

trait RoleDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($role = Entities\Privilege::where('section', 'role')->where('id', $request->get('id'))->first()) {
            $role->load('childRelations');
            return app('ResponseSingular')->send($role);
        }
        return app('ResponseError')->sendMessage('Role not found', 404);
    }
}
