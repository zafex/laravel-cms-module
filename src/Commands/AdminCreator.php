<?php

namespace Apiex\Commands;

use Apiex\Entities\Role;
use Apiex\Entities\RoleUser;
use Apiex\Entities\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminCreator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Create new admin';

    /**
     * @var string
     */
    protected $signature = 'apiex:create-admin';

    /**
     * @return mixed
     */
    public function handle()
    {
        $roles = Role::all()->map(function ($role) {
            return $role->name;
        })->toArray();

        $roleName = $this->choice('Select Role Admin', $roles);
        $role = Role::where('name', $roleName)->first();

        $name = $this->ask('Type your username');
        $email = $this->ask('Type your email');
        $password = $this->secret('Type your password');
        $confirm = $this->secret('Confirm your password');

        $this->info('Username: ' . $name);
        $this->info('Email: ' . $email);
        $this->info('Assign to role ' . $roleName . ' which role_id=' . $role->id);

        if ($this->confirm('Do you wish to continue ?')) {

            $credentials = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $confirm,
            ];

            $validator = Validator::make($credentials, [
                'name' => 'required|string|max:255|unique:user',
                'email' => 'required|string|email|max:255|unique:user',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                $this->info('Can not create user. See error messages below:');
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }
                return 1;
            }

            try {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);
                $roleUser = RoleUser::create([
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ]);
                $this->info('Admin which user ' . $name . ' succesfully created with role ' . $roleName);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
