<?php

namespace Apiex\Commands;

use Apiex\Entities\Privilege;
use Apiex\Entities\PrivilegeAssignment;
use Illuminate\Console\Command;

class AdminroleGenerator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Generate role with all permissions';

    /**
     * @var string
     */
    protected $signature = 'apiex:generate-role-admin {name=Admin} {--description=}';

    public function handle()
    {
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

        foreach ($permissions as $permission) {
            $assign = PrivilegeAssignment::firstOrCreate([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ]);
            $this->info('Assigned permission ' . $permission->name . ' to ' . $name);
        }
    }
}
