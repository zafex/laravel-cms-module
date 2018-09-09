<?php

namespace Apiex\Commands;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Entities\Privilege;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class PermissionGenerator extends Command
{
    /**
     * @var string
     */
    protected $description = 'Generate permissions base on route\'s name';

    /**
     * @var string
     */
    protected $signature = 'apiex:generate-permissions';

    public function handle()
    {
        $collections = Route::getRoutes();
        $section = 'permission';
        $count = 0;
        foreach ($collections as $route) {
            if ($name = $route->getName()) {
                $privilege = Privilege::updateOrCreate(compact('name', 'section'), [
                    'description' => $route->uri,
                ]);
                $this->info('Created privilege ' . $privilege->name . ' (desc: ' . $privilege->description . ')');
                $count++;
            }
        }
        $this->info($count . ' Permissions has been generated');
        $this->info('Try command "apiex:generate-admin" for create a new role with all permissions');
    }
}
