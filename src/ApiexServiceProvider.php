<?php

namespace Apiex;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Entities;
use Apiex\Observers\AuditLog;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class ApiexServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->publishMigrations('2018_09_01_125300_apiex');

        $this->commands('apiex.command.user-creator');
        $this->commands('apiex.command.admin-generator');
        $this->commands('apiex.command.permission-generator');

        Entities\Privilege::observe(AuditLog::class);
        Entities\PrivilegeAssignment::observe(AuditLog::class);
        Entities\PrivilegeUser::observe(AuditLog::class);
        Entities\User::observe(AuditLog::class);
        Entities\UserInfo::observe(AuditLog::class);
        Entities\UserPermission::observe(AuditLog::class);
        Entities\Setting::observe(AuditLog::class);
        Entities\Menu::observe(AuditLog::class);
        Entities\MenuItem::observe(AuditLog::class);
        Entities\Workflow::observe(AuditLog::class);
        Entities\WorkflowStep::observe(AuditLog::class);
        Entities\WorkflowVerificator::observe(AuditLog::class);
    }

    public function register()
    {
        parent::register();

        $this->app->bind('ResponseError', Common\ResponseError::class);
        $this->app->bind('ResponseSingular', Common\ResponseSingular::class);
        $this->app->bind('ResponseCollection', Common\ResponseCollection::class);
        $this->app->bind('LogCreation', Common\LogCreation::class);

        $this->app->bind('apiex.command.user-creator', Commands\UserCreator::class);
        $this->app->bind('apiex.command.admin-generator', Commands\AdminGenerator::class);
        $this->app->bind('apiex.command.permission-generator', Commands\PermissionGenerator::class);

        $this->app->singleton('settings', Helpers\Settings::class);
        $this->app->singleton('privileges', Helpers\Privileges::class);
    }

    /**
     * @param $prefix
     */
    protected function publishMigrations($prefix)
    {
        $path = dirname(__DIR__) . '/migrations';
        $this->publishes([
            $path . "/{$prefix}_audit.php" => database_path("migrations/{$prefix}_audit.php"),
            $path . "/{$prefix}_audit_detail.php" => database_path("migrations/{$prefix}_audit_detail.php"),
            $path . "/{$prefix}_menu.php" => database_path("migrations/{$prefix}_menu.php"),
            $path . "/{$prefix}_menu_item.php" => database_path("migrations/{$prefix}_menu_item.php"),
            $path . "/{$prefix}_privilege.php" => database_path("migrations/{$prefix}_privilege.php"),
            $path . "/{$prefix}_privilege_assignment.php" => database_path("migrations/{$prefix}_privilege_assignment.php"),
            $path . "/{$prefix}_privilege_user.php" => database_path("migrations/{$prefix}_privilege_user.php"),
            $path . "/{$prefix}_setting.php" => database_path("migrations/{$prefix}_setting.php"),
            $path . "/{$prefix}_user.php" => database_path("migrations/{$prefix}_user.php"),
            $path . "/{$prefix}_user_info.php" => database_path("migrations/{$prefix}_user_info.php"),
            $path . "/{$prefix}_user_permission.php" => database_path("migrations/{$prefix}_user_permission.php"),
            $path . "/{$prefix}_workflow.php" => database_path("migrations/{$prefix}_workflow.php"),
            $path . "/{$prefix}_workflow_step.php" => database_path("migrations/{$prefix}_workflow_step.php"),
            $path . "/{$prefix}_workflow_verificator.php" => database_path("migrations/{$prefix}_workflow_verificator.php"),
        ], 'migrations');
    }
}
