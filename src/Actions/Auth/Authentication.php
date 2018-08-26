<?php

namespace Apiex\Actions\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

trait Authentication
{
    /**
     * @param Request $request
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('name', 'password');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return app('ResponseError')->sendValidation($validator, 'authenticate');
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return app('ResponseError')->sendMessage(__('invalid_credentials'), 400);
            }
        } catch (JWTException $e) {
            return app('ResponseError')->sendMessage(__('could_not_create_token'), 500);
        }

        return app('ResponseSingular')->send($token);
    }
}
