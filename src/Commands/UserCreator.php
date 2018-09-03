<?php

namespace Apiex\Commands;

use Apiex\Entities\Privilege;
use Apiex\Entities\PrivilegeUser;
use Apiex\Entities\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserCreator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * @var string
     */
    protected $signature = 'apiex:create-user';

    /**
     * @return mixed
     */
    public function handle()
    {
        return $this->createUser();
    }

    /**
     * @return mixed
     */
    protected function createUser()
    {
        $roles = Privilege::where('section', 'role')->get()->map(function ($role) {
            return $role->name;
        })->toArray();

        $roleName = $this->choice('Select Role', $roles);
        $role = Privilege::where('name', $roleName)->where('section', 'role')->first();

        $name = $this->ask('Type your username');
        $email = $this->ask('Type your email');
        $password = $this->secret('Type your password');
        $confirm = $this->secret('Confirm your password');

        $this->info('Username: ' . $name);
        $this->info('Email: ' . $email);
        $this->info('Assign to role ' . $roleName . ' which role_id=' . $role->id);

        if ($this->confirm('Do you wish to continue ?', 'yes')) {

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
                if ($this->confirm('Try again ?', 'yes')) {
                    return $this->createUser();
                }
                return 1;
            }

            try {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'status' => 1,
                    'password' => Hash::make($password),
                ]);
                $roleUser = PrivilegeUser::create([
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ]);
                $this->info('User which username ' . $name . ' succesfully created with role ' . $roleName);
                if ($this->confirm('Create user again ?')) {
                    return $this->createUser();
                }
            } catch (Exception $e) {
                $this->error($e->getMessage());
                if ($this->confirm('Try again ?', 'yes')) {
                    return $this->createUser();
                }
            }
        }
    }
}
