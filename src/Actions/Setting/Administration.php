<?php

namespace Apiex\Actions\Setting;

use Apiex\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

trait Administration
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if ($opt = Entities\Setting::where('id', $request->get('id'))->first()) {
            // do nothing
        } elseif ($opt = Entities\Setting::where('section', $request->get('section'))->first()) {
            // do nothing
        } else {
            return app('ResponseError')->withMessage(__('setting_not_found'))->send(404);
        }
        $value = json_decode($opt->value);
        $options = [
            'id' => $opt->id,
            'section' => $opt->section,
            'value' => json_last_error() === JSON_ERROR_NONE ? $value : $opt->value,
        ];
        return app('ResponseSingular')->setItem($options)->send();
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $options = $request->all();
        $successArray = [];
        foreach ($options as $key => $value) {
            $section = preg_replace('/[^a-z0-9\-\.\:]/', '_', $key);
            $sectionArray = preg_split('/\:/', $section, -1, PREG_SPLIT_NO_EMPTY);
            if (array_key_exists(1, $sectionArray)) {
                if (method_exists($this, $sectionArray[0] . 'FilterHandler')) {
                    $value = $this->{$sectionArray[0] . 'FilterHandler'}($value);
                    $section = implode(':', array_slice($sectionArray, 1));
                }
            }
            $value = is_scalar($value) ? ($value ?: ''): json_encode($value);
            $validator = Validator::make(compact('section', 'value'), [
                'section' => 'required|string|max:255',
            ]);
            if (!$validator->fails()) {
                Entities\Setting::updateOrCreate(compact('section'), [
                    'value' => $value,
                ]);
                $successArray[] = __("{$section} was successfully stored.");
            }
        }
        if ($successArray) {
            $response = app('ResponseCollection');
            foreach ($successArray as $success) {
                $response->addCollection($success);
            }
            return $response->send();
        }
        return app('ResponseError')->withMessage(__('nothing_to_store'))->send(404);
    }

    /**
     * @param $value
     */
    protected function hashFilterHandler($value)
    {
        if (is_scalar($value)) {
            return Hash::make($value);
        } else {
            return array_map([$this, __METHOD__], (array) $value);
        }
    }
}
