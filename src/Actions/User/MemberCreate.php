<?php

namespace Apiex\Actions\User;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

trait MemberCreate
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
}
