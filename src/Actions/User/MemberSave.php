<?php

namespace Apiex\Actions\User;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait MemberSave
{
    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:user',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return app('ResponseError')->withValidation($validator, 'create')->send();
        }

        $user = new Entities\User;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return app('ResponseSingular')->setItem($user)->send(201);
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        try {
            $rules = [];
            $user_id = $request->get('id');
            $user = Entities\User::where('id', $user_id)->first();

            if ($request->has('name')) {
                $rules['name'] = [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('user')->where(function ($query) use ($user) {
                        return $query->where('id', '<>', $user->id);
                    }),
                ];
            }

            if ($request->has('email')) {
                $rules['email'] = [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('user')->where(function ($query) use ($user) {
                        return $query->where('id', '<>', $user->id);
                    }),
                ];
            }

            if ($request->has('password')) {
                $rules['password'] = 'required|string|min:6|confirmed';
            }

            if ($rules) {
                $validator = Validator::make($request->only(['name', 'email', 'password', 'password_confirmation']), $rules);
                if ($validator->fails()) {
                    return app('ResponseError')->withValidation($validator, 'update')->send();
                }

                foreach ($request->only(['name', 'email']) as $field => $value) {
                    $user->{$field} = $value;
                }

                if ($request->has('password')) {
                    $user->password = Hash::make($request->get('password'));
                }
                $user->save();
            }

            foreach ($request->except(['name', 'email', 'password', 'password_confirmation']) as $section => $value) {
                Entities\UserInfo::updateOrCreate(compact('user_id', 'section'), [
                    'value' => $value ?: '',
                ]);
            }
            return app('ResponseSingular')->setItem(__('User was successfully updated.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
