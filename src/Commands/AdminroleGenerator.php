<?php

namespace Apiex\Commands;

use Apiex\Entities\Privilege;
use Apiex\Entities\PrivilegeRole;
use Apiex\Entities\Role;
use Illuminate\Console\Command;

class AdminroleGenerator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Generate role with all privileges';

    /**
     * @var string
     */
    protected $signature = 'apiex:generate-role-admin {name=Admin} {--description=}';

    public function handle()
    {
        $raw = $this->argument('name');
        $description = $this->option('description') ?: $raw;
        $name = preg_replace('/[^a-z0-9\.]+/', '-', strtolower($raw));
        $role = Role::firstOrCreate([
            'name' => $name,
            'description' => $description,
        ]);
        $this->info('Created role ' . $name . ' (desc: ' . $description . ')');

        // get all privileges
        $privileges = Privilege::all();
        foreach ($privileges as $privilege) {
            $assign = PrivilegeRole::firstOrCreate([
                'privilege_id' => $privilege->id,
                'role_id' => $role->id,
            ]);
            $this->info('Assigned privilege ' . $privilege->name . ' to ' . $name);
        }
    }
}
