<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Illuminate\Http\Request;

trait RoleList
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $model = Entities\Privilege::where('section', 'role')->paginate($request->query('per_page') ?: 10);
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
            $object->load('childRelations');
            $response->addCollection($object);
        }

        return $response->send(200);
    }
}
