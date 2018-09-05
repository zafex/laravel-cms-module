<?php

namespace Apiex\Actions\Workflow;

use Apiex\Entities;
use Illuminate\Http\Request;

trait MasterList
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $model = Entities\Workflow::paginate($request->query('per_page') ?: 10);
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
            $object->load(['steps.verificators.user']);
            $response->addCollection($object);
        }

        return $response->send(200);
    }
}
