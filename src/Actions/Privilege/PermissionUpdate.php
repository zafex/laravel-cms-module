<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait PermissionUpdate
{
    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        try {
            $permission_id = $request->get('id');
            $permission = Entities\Privilege::where('id', $permission_id)
                ->where('section', 'permission')
                ->first();
            if (!$permission) {
                return app('ResponseError')->withMessage('permission_not_found')->send(404);
            }

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('privilege')->where(function ($query) use ($permission) {
                        return $query->where('id', '<>', $permission->id)->where('section', 'permission');
                    }),
                ],
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return app('ResponseError')->withValidation($validator, 'update_permission')->send();
            }

            $permission->name = $required->get('name');
            $permission->description = $required->get('description');
            $permission->save();

            return app('ResponseSingular')->setItem(__('Permission was successfully updated.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
