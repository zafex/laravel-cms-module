<?php

namespace Apiex\Actions\Privilege;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait Administration
{
    /**
     * @param Request $request
     */
    public function show(Request $request)
    {
        $type = $request->get('type') ?: 'main';
        $menus = Entities\Menu::where('type', $type)->get();
        $response = app('ResponseCollection');
        foreach ($menus as $menu) {
            $response->addCollection($menu);
        }
        return $response->send();
    }

    public function save(Request $request)
    {
        $type = $request->get('type') ?: 'main';
        $ids = $request->get('ids') ?: [$request->get('id')];
    }
}
