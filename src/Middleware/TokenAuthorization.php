<?php

namespace Apiex\Middleware;

use Apiex\Entities\Privilege;
use Apiex\Entities\UserPermission;
use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TokenAuthorization extends BaseMiddleware
{
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
            $user = JWTAuth::parseToken()->authenticate();
            $permission = Privilege::where('section', 'permission')->where('name', $request->route()->getName())->first();

            if (!$user || !$permission) {
                return app('ResponseError')->sendMessage(__('page_not_found'), 404);
            }

            $userPermission = UserPermission::where('user_id', $user->id)->where('permission_id', $permission->id)->first();
            $permissions = $user->permissions->map(function ($object) {
                return $object->permission->id;
            })->toArray();

            if (!in_array($permission->id, $permissions)) {
                if (!$userPermission) {
                    return app('ResponseError')->sendMessage(__('not_allowed_access'), 403);
                } else {
                    if (
                        ($request->isMethod('post') && $request->input('id') != $userPermission->object_id) ||
                        ($request->isMethod('get') && $request->query('id') != $userPermission->object_id)
                    ) {
                        return app('ResponseError')->sendMessage(__('not_allowed_access'), 403);
                    }
                }
            }

        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return app('ResponseError')->sendMessage(__('token_invalid'), 400);
            } elseif ($e instanceof TokenExpiredException) {
                return app('ResponseError')->sendMessage(__('token_expired'), 400);
            } elseif ($e instanceof JWTException) {
                return app('ResponseError')->sendMessage(__('authorization_token_not_found'), 400);
            } else {
                return app('ResponseError')->sendException($e);
            }
        }

        return $next($request);
    }
}
