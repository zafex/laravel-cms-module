<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Illuminate\Http\Request;

trait PermissionList
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $model = Entities\Privilege::where('section', 'permission')->paginate($request->query('per_page') ?: 10);
        $items = [];
        foreach ($model as $object) {
            $items[] = $object;
        }

        return app('ResponseCollection')->send($items, 200, [
            'count' => $model->total(),
            'per_page' => $model->perPage(),
            'current_page' => $model->currentPage(),
            'links' => [
                'self' => $model->url($model->currentPage()),
                'first_page' => $model->url(1),
                'last_page' => $model->url($model->lastPage()),
                'next_page' => $model->nextPageUrl(),
                'prev_page' => $model->previousPageUrl(),
            ],
        ]);
    }
}
