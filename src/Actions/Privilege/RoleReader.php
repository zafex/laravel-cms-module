<?php

namespace Apiex\Actions\Privilege;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Illuminate\Http\Request;

trait RoleReader
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($role = Entities\Privilege::where('section', 'role')->where('id', $request->get('id'))->first()) {
            $role->load('childRelations');
            return app('ResponseSingular')->setItem($role)->send();
        }
        return app('ResponseError')->withMessage(__('role_not_found'))->send(404);
    }

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
