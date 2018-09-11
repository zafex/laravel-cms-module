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
use Apiex\Helpers\Privileges;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\JWTAuth;

trait Information
{
    /**
     * @param Request $request
     */
    public function detail(Request $request, JWTAuth $auth, Privileges $priv)
    {
        $token = $auth->parseToken();
        $user = $token->authenticate();
        $privileges = $priv->all();

        return app('ResponseSingular')->setItem(compact('user', 'payload', 'privileges'))->send();
    }

    /**
     * @param Request $request
     */
    public function update(Request $request, JWTAuth $auth)
    {
        try {

            $token = $auth->parseToken();
            $user = $token->authenticate();
            $user_id = $user->id;

            $rules = [];
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

            return app('ResponseSingular')->setItem(__('update_success'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
