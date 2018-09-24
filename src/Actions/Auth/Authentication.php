<?php

namespace Apiex\Actions\Auth;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Helpers\Privileges;
use Apiex\Helpers\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

trait Authentication
{
    /**
     * @param Request    $request
     * @param Privileges $privileges
     * @param Settings   $settings
     * @param JWTAuth    $auth
     */
    public function authenticate(Request $request, Privileges $privileges, Settings $settings, JWTAuth $auth)
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
            $token = $auth->attempt($credentials);

            if (!$token) {
                return app('ResponseError')->withMessage(__('invalid_credentials'))->send(400);
            }

            app('LogCreation')->make('LOGIN', $auth->user());

        } catch (JWTException $e) {
            return app('ResponseError')->withMessage(__('could_not_create_token'))->send(500);
        }

        // re-cache all privileges
        $privileges->load();

        // re-cache all settings information
        $settings->load();

        return app('ResponseSingular')->setItem($token)->send(200);
    }

    /**
     * @param Request    $request
     * @param Privileges $privileges
     * @param Settings   $settings
     * @param JWTAuth    $auth
     */
    public function revalidate(Request $request, Privileges $privileges, Settings $settings, JWTAuth $auth)
    {
        try {

            if (!$auth->parser()->setRequest($request)->hasToken()) {
                throw new JWTException('Token not provided');
            }

            $token = $auth->parseToken();

            if ($authenticate = $token->authenticate()) {
                // re-cache all privileges
                $privileges->load();
                // re-cache all settings information
                $settings->load();

                return app('ResponseSingular')->setItem($token)->send();
            }

            throw new Exception(__('user_not_found'));

        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return app('ResponseError')->withMessage(__('token_invalid'))->send(400);
            } elseif ($e instanceof TokenExpiredException) {
                return app('ResponseError')->withMessage(__('token_expired'))->send(400);
            } elseif ($e instanceof JWTException) {
                return app('ResponseError')->withMessage(__('authorization_token_not_found'))->send(400);
            } else {
                return app('ResponseError')->withException($e)->send();
            }
        }
    }
}
