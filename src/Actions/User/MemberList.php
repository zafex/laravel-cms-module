<?php

namespace Apiex\Actions\User;

use Apiex\Entities\User;
use Illuminate\Http\Request;

trait MemberList
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $users = User::paginate($request->query('page-size') ?: 10);
        $items = [];
        foreach ($users as $user) {
            $items[] = $user;
        }

        return app('ResponseCollection')->send($items, 200, [
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
    }
}
