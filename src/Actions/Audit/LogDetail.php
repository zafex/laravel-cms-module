<?php

namespace Apiex\Actions\Audit;

use Apiex\Entities\Audit as LogModel;
use Illuminate\Http\Request;

trait LogDetail
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($audit = LogModel::where('id', $request->get('id'))->first()) {
            $audit->load('details');
            return app('ResponseSingular')->setItem($audit)->send(200);
        }
        return app('ResponseError')->withMessage(__('log_not_found'))->send(404);
    }
}
