<?php

namespace Apiex\Actions\Audit;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

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
        $response = app('ResponseCollection');
        $response->withMeta([
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
        foreach ($model as $object) {
            $response->addCollection($object);
        }

        return $response->send(200);
    }
}
