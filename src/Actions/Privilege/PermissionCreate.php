<?php

namespace Apiex\Actions\Privilege;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait PermissionCreate
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
                        return $query->where('section', 'permission');
                    }),
                ],
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return app('ResponseError')->withValidation($validator, 'create_permission')->send();
            }

            $permission = new Entities\Privilege;
            $permission->name = $request->get('name');
            $permission->description = $request->get('description');
            $permission->section = 'permission';
            $permission->save();

            return app('ResponseSingular')->setItem(__('Permission was successfully created.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
