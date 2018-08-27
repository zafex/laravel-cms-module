<?php

namespace Apiex\Actions\Audit;

use Apiex\Entities\Audit as LogModel;
use Illuminate\Http\Request;

trait LogList
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $model = LogModel::paginate($request->query('per_page') ?: 10);
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
