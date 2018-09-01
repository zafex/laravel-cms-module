<?php

namespace Apiex\Actions\Menu;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ItemUpdate
{
    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        try {
            $item_id = $request->get('id');
            $item = Entities\MenuItem::where('id', $item_id)->first();
            if (!$item) {
                return app('ResponseError')->withMessage('menu_item_not_found')->send(404);
            }

            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255',
                'description' => 'string|max:255',
                'privilege_id' => 'integer',
            ]);

            if ($validator->fails()) {
                return app('ResponseError')->withValidation($validator, 'update_menu_item')->send();
            }

            $item->label = $request->get('label');
            $item->description = $request->get('description');
            $item->icon = $request->get('icon') ?: '#';
            $item->url = $request->get('url') ?: '#';
            $item->privilege_id = $request->get('privilege_id') ?: 0;
            $menu->save();

            return app('ResponseSingular')->setItem(__('Menu Item was successfully updated.'))->send();

        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
