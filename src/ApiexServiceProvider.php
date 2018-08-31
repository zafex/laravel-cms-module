<?php

namespace Apiex;

use Apiex\Entities;
use Apiex\Observers\AuditLog;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class ApiexServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->publishMigrations();

        $this->commands('apiex.command.admin-creator');
        $this->commands('apiex.command.adminrole-generator');
        $this->commands('apiex.command.permission-generator');

        Entities\Privilege::observe(AuditLog::class);
        Entities\PrivilegeAssignment::observe(AuditLog::class);
        Entities\PrivilegeUser::observe(AuditLog::class);
        Entities\User::observe(AuditLog::class);
        Entities\UserInfo::observe(AuditLog::class);
        Entities\UserPermission::observe(AuditLog::class);
    }

    public function register()
    {
        parent::register();

        $this->app->bind('ResponseError', Common\ResponseError::class);
        $this->app->bind('ResponseSingular', Common\ResponseSingular::class);
        $this->app->bind('ResponseCollection', Common\ResponseCollection::class);

        $this->app->bind('apiex.command.admin-creator', Commands\AdminCreator::class);
        $this->app->bind('apiex.command.adminrole-generator', Commands\AdminroleGenerator::class);
        $this->app->bind('apiex.command.permission-generator', Commands\PermissionGenerator::class);
    }

    protected function publishMigrations()
    {
        $path = dirname(__DIR__) . '/migrations';
        $prefix = date('Y_m_d_His');
        $this->publishes([
            $path . '/audit.php' => database_path("migrations/{$prefix}_audit.php"),
            $path . '/audit_detail.php' => database_path("migrations/{$prefix}_audit_detail.php"),
            $path . '/menu.php' => database_path("migrations/{$prefix}_menu.php"),
            $path . '/menu_item.php' => database_path("migrations/{$prefix}_menu_item.php"),
            $path . '/privilege.php' => database_path("migrations/{$prefix}_privilege.php"),
            $path . '/privilege_assignment.php' => database_path("migrations/{$prefix}_privilege_assignment.php"),
            $path . '/privilege_user.php' => database_path("migrations/{$prefix}_privilege_user.php"),
            $path . '/user.php' => database_path("migrations/{$prefix}_user.php"),
            $path . '/user_info.php' => database_path("migrations/{$prefix}_user_info.php"),
            $path . '/user_permission.php' => database_path("migrations/{$prefix}_user_permission.php"),
        ], 'migrations');
    }
}
