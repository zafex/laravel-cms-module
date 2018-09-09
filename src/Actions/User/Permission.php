<?php

namespace Apiex\Actions\User;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait Permission
{
    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'object_id' => 'required|integer',
            'permission_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return app('ResponseError')->withValidation($validator, 'create_user_permission')->send();
        }

        Entities\UserPermission::firstOrCreate([
            'user_id' => $request->get('id'),
            'object_id' => $request->get('object_id'),
            'permission_id' => $request->get('permission_id'),
        ]);
        return app('ResponseSingular')->setItem(__('UserPermission was successfully created.'))->send();
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'object_id' => 'required|integer',
            'permission_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return app('ResponseError')->withValidation($validator, 'delete_user_permission')->send();
        }

        $permission = Entities\UserPermission::where('user_id', $request->get('id'))
            ->where('object_id', $request->get('object_id'))
            ->where('permission_id', $request->get('permission_id'))
            ->get();

        if ($permission) {
            $permission->delete();
        }
        return app('ResponseSingular')->setItem(__('UserPermission was successfully deleted.'))->send();
    }
}
