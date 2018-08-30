<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait RoleCreate
{
    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('privilege')->where(function ($query) {
                        return $query->where('section', 'role');
                    }),
                ],
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return app('ResponseError')->withValidation($validator, 'create_role')->send();
            }

            $role = new Entities\Role;
            $role->name = $required->get('name');
            $role->description = $required->get('description');
            $role->section = 'role';
            $role->save();

            return app('ResponseSingular')->setItem(__('Roles was successfully created.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
