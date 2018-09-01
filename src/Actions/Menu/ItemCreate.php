<?php

namespace Apiex\Actions\Menu;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait MasterCreate
{
    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255',
                'description' => 'string|max:255',
                'menu_id' => 'required|integer',
                'parent_id' => 'integer',
                'privilege_id' => 'integer',
            ]);

            if ($validator->fails()) {
                return app('ResponseError')->withValidation($validator, 'create_menu_item')->send();
            }

            $item = new Entities\MenuItem;
            $item->label = $request->get('label');
            $item->description = $request->get('description');
            $item->icon = $request->get('icon') ?: '#';
            $item->url = $request->get('url') ?: '#';
            $item->menu_id = $request->get('menu_id');
            $item->parent_id = $request->get('parent_id') ?: 0;
            $item->privilege_id = $request->get('privilege_id') ?: 0;
            $item->save();

            return app('ResponseSingular')->setItem(__('Menu item was successfully created.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
