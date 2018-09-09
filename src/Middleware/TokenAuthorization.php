<?php

namespace Apiex\Middleware;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class TokenAuthorization
{
    /**
     * @var mixed
     */
    protected $auth;

    /**
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!$this->auth->parser()->setRequest($request)->hasToken()) {
                throw new JWTException('Token not provided');
            }

            $token = $this->auth->parseToken();
            $user_id = $token->getPayload()->get('sub');
            $permission = $request->route()->getName();

            if (false == app('privileges')->hasAccess($permission, 'permission', null, $user_id)) {
                $object_id = $request->isMethod('get') ? $request->query('id') : $request->input('id');
                if (false == app('privileges')->hasAccess($permission, 'permission', $object_id ?: null, $user_id)) {
                    return app('ResponseError')->withMessage(__('not_allowed_access'))->send(403);
                }
            }

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

        return $next($request);
    }
}
