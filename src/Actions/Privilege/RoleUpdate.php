<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait RoleUpdate
{
    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        try {
            $role_id = $request->get('id');
            $role = Entities\Privilege::where('id', $role_id)
                ->where('section', 'role')
                ->first();
            if (!$role) {
                return app('ResponseError')->withMessage('role_not_found')->send(404);
            }

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('privilege')->where(function ($query) use ($role) {
                        return $query->where('id', '<>', $role->id)->where('section', 'role');
                    }),
                ],
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return app('ResponseError')->withValidation($validator, 'update_role')->send();
            }

            $role->name = $request->get('name');
            $role->description = $request->get('description');
            $role->save();

            return app('ResponseSingular')->setItem(__('Roles was successfully updated.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
