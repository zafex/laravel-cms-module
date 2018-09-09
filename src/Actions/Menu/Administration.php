<?php

namespace Apiex\Actions\Menu;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait Administration
{
    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return app('ResponseError')->withValidation($validator, 'create_menu')->send();
            }

            $menu = new Entities\Menu;
            $menu->label = $request->get('label');
            $menu->description = $request->get('description');
            $menu->save();

            return app('ResponseSingular')->setItem(__('Menu was successfully created.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        if ($menu = Entities\Menu::where('id', $request->get('id'))->first()) {
            foreach ($menu->items() as $item) {
                $item->delete();
            }
            $menu->delete();
            return app('ResponseSingular')->setItem(__('Menu was successfully deleted.'))->send();
        } else {
            return app('ResponseError')->withMessage('menu_not_found')->send(404);
        }
    }

    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($menu = Entities\Menu::where('id', $request->get('id'))->first()) {
            $menu->load('items');
            return app('ResponseSingular')->setItem($menu)->send();
        }
        return app('ResponseError')->withMessage('menu_not_found')->send(404);
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $model = Entities\Menu::paginate($request->query('per_page') ?: 10);
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
            $object->load('items');
            $response->addCollection($object);
        }

        return $response->send(200);
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        if ($menu = Entities\Menu::where('id', $request->get('id'))->first()) {

            $rules = [];
            if ($request->has('label')) {
                $rules['label'] = 'required|string|max:255';
            }
            if ($request->has('description')) {
                $rules['description'] = 'required|string';
            }
            if ($rules) {
                $validator = Validator::make($request->only(['label', 'description']), $rules);
                if ($validator->fails()) {
                    return app('ResponseError')->withValidation($validator, 'update_menu')->send();
                }
                foreach ($request->only(['label', 'description']) as $key => $value) {
                    $menu->{$key} = $value;
                }
                $menu->save();
            }

            $items = $request->get('items') ?: [];
            $itemIds = [];
            $mapItems = [];
            foreach ($items as $item) {
                $mapItems[$item['id']] = $item['parent_id'];
                $itemIds[] = $item['id'];
            }

            $objectItems = Entities\MenuItem::where('menu_id', $menu->id)
                ->whereIn('id', $itemIds)
                ->get();

            foreach ($objectItems as $objectItem) {
                $objectItem->parent_id = array_key_exists($objectItem->id, $mapItems) ? $mapItems[$objectItem->id] : 0;
                $objectItem->save();
            }

            return app('ResponseSingular')->setItem(__('Menu was successfully updated.'))->send();

        } else {
            return app('ResponseError')->withMessage('menu_not_found')->send(404);
        }
    }
}
