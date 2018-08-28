<?php

namespace Apiex\Actions\User;

use Apiex\Entities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait MemberUpdate
{
    /**
     * @param  Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        try {
            $rules = [];
            $user_id = $request->get('id');
            $user = Entities\User::where('id', $user_id)->first();

            if ($request->has('name')) {
                $rules['name'] = [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('user')->where(function ($query) use ($user) {
                        return $query->where('id', '<>', $user->id);
                    }),
                ];
            }

            if ($request->has('email')) {
                $rules['email'] = [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('user')->where(function ($query) use ($user) {
                        return $query->where('id', '<>', $user->id);
                    }),
                ];
            }

            if ($request->has('password')) {
                $rules['password'] = 'required|string|min:6|confirmed';
            }

            if ($rules) {
                $validator = Validator::make($request->only(['name', 'email', 'password', 'password_confirmation']), $rules);
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
            }

            foreach ($request->except(['name', 'email', 'password', 'password_confirmation']) as $section => $value) {
                Entities\UserInfo::updateOrCreate(compact('user_id', 'section'), [
                    'value' => $value ?: '',
                ]);
            }
            return app('ResponseSingular')->send('User was successfully updated.');

        } catch (Exception $e) {
            return app('ResponseError')->sendException($e);
        }
    }
}
