<?php

namespace Apiex\Commands;

use Apiex\Entities\Privilege;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class PrivilegeGenerator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Generate privileges base on route\'s name';

    /**
     * @var string
     */
    protected $signature = 'apiex:generate-privileges';

    public function handle()
    {
        $collections = Route::getRoutes();
        foreach ($collections as $route) {
            if ($name = $route->getName()) {
                $privilege = Privilege::firstOrCreate([
                    'name' => $name,
                    'description' => $route->uri,
                ]);
                $this->info('Created privilege ' . $privilege->name . ' (desc: ' . $privilege->description . ')');
            }
        }
    }
}
