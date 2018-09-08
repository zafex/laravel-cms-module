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
            return app('ResponseError')->withValidation($validator, 'authenticate')->send();
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return app('ResponseError')->withMessage(__('invalid_credentials'))->send(400);
            }
        } catch (JWTException $e) {
            return app('ResponseError')->withMessage(__('could_not_create_token'))->send(500);
        }

        // re-cache all privileges
        app('privileges')->load();

        return app('ResponseSingular')->setItem($token)->send(200);
    }
}
