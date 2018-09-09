<?php

namespace Apiex\Commands;

/**
 * @package zafex/apiexlara
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 * @link https://github.com/zafex
 */

use Apiex\Entities\Privilege;
use Apiex\Entities\PrivilegeAssignment;
use Illuminate\Console\Command;

class AdminGenerator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Generate role with all permissions';

    /**
     * @var string
     */
    protected $signature = 'apiex:generate-admin {name=Admin} {--description=}';

    public function handle()
    {
        $this->info('This command is for generate admin\'s role, not user admin.');
        if ($this->confirm('Do you wish to continue ?', 'yes')) {
            $raw = $this->argument('name');
            $description = $this->option('description') ?: $raw;
            $name = preg_replace('/[^a-z0-9\.]+/', '-', strtolower($raw));
            $section = 'role';
            $role = Privilege::updateOrCreate(compact('name', 'section'), [
                'description' => $description,
            ]);
            $this->info('Created role ' . $name . ' (desc: ' . $description . ')');

            // get all permissions
            $permissions = Privilege::where('section', 'permission')->get();
            $count = 0;
            foreach ($permissions as $permission) {
                $assign = PrivilegeAssignment::firstOrCreate([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);
                $this->info('Assigned permission ' . $permission->name . ' to ' . $name);
                $count++;
            }
            $this->info('Role ' . $name . ' has been created with ' . $count . ' permissions.');
            $this->info('Try command "apiex:create-user" for create a new user.');
        }
    }
}
