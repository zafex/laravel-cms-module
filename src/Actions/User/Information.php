<?php

namespace Apiex\Actions\User;

use Apiex\Entities\User;
use Apiex\Entities\UserInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait Information
{
    /**
     * @param Request $request
     */
    public function detail(Request $request)
    {
        $user = auth()->user()->load('details');

        return app('ResponseSingular')->send($user);
    }

    /**
     * @param Request $request
     */
    public function setting(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            foreach ($request->all() as $section => $value) {
                UserInfo::updateOrCreate(compact('user_id', 'section'), [
                    'value' => $value,
                ]);
            }
            return app('ResponseSingular')->send('Detail was successfully updated.');
        } catch (Exception $e) {
            return app('ResponseError')->sendException($e);
        }
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        try {

            $user = auth()->user();

            $rules = [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('user')->where(function ($query) use ($user) {
                        return $query->where('id', '<>', $user->id);
                    }),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('user')->where(function ($query) use ($user) {
                        return $query->where('id', '<>', $user->id);
                    }),
                ],
            ];

            if ($request->has('password')) {
                $rules['password'] = 'required|string|min:6|confirmed';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return app('ResponseError')->sendValidation($validator, 'update');
            }

            foreach ($request->only(['name', 'email']) as $field => $value) {
                $user->{$field} = $value;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->get('password'));
            }

            $user->save();

            return app('ResponseSingular')->send('User was successfully updated.');

        } catch (Exception $e) {
            return app('ResponseError')->sendException($e);
        }
    }
}
