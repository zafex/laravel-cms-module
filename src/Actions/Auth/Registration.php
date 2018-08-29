<?php

namespace Apiex\Actions\Auth;

use Apiex\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

trait Registration
{
    /**
     * @param Request $request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:user',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return app('ResponseError')->withValidation($validator, 'register')->send();
        }

        $user = new Entities\User;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $token = JWTAuth::fromUser($user);

        return app('ResponseSingular')->setItem(compact('user', 'token'))->send(201);
    }
}
