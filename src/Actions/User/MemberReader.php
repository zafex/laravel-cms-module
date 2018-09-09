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
use Illuminate\Http\Request;

trait MemberReader
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($user = Entities\User::where('id', $request->get('id'))->first()) {
            $user->load(['details', 'roles']);
            return app('ResponseSingular')->setItem($user)->send();
        }
        return app('ResponseError')->withMessage(__('user_not_found'))->send(404);
    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $users = Entities\User::paginate($request->query('per_page') ?: 10);
        $response = app('ResponseCollection');
        $response->withMeta([
            'count' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
            'links' => [
                'self' => $users->url($users->currentPage()),
                'first_page' => $users->url(1),
                'last_page' => $users->url($users->lastPage()),
                'next_page' => $users->nextPageUrl(),
                'prev_page' => $users->previousPageUrl(),
            ],
        ]);
        foreach ($users as $user) {
            $response->addCollection($user);
        }

        return $response->send();
    }
}
