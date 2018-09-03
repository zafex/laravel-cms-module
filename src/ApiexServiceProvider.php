<?php

namespace Apiex;

use Apiex\Common\Settings;
use Apiex\Entities;
use Apiex\Observers\AuditLog;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class ApiexServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations');

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

        $this->app->bind('apiex.command.user-creator', Commands\UserCreator::class);
        $this->app->bind('apiex.command.admin-generator', Commands\AdminGenerator::class);
        $this->app->bind('apiex.command.permission-generator', Commands\PermissionGenerator::class);

        $this->app->singleton('settings', Settings::class);
    }
}
