<?php

namespace Apiex;

use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class ApiexServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations');

        $this->commands('apiex.command.admin-creator');
        $this->commands('apiex.command.adminrole-generator');
        $this->commands('apiex.command.permission-generator');
    }

    public function register()
    {
        parent::register();

        $this->app->bind('ResponseError', Common\ResponseError::class);
        $this->app->bind('ResponseSingular', Common\ResponseSingular::class);
        $this->app->bind('ResponseCollection', Common\ResponseCollection::class);

        $this->app->bind('apiex.command.admin-creator', function () {
            return new Commands\AdminCreator;
        });

        $this->app->bind('apiex.command.adminrole-generator', function () {
            return new Commands\AdminroleGenerator;
        });

        $this->app->bind('apiex.command.permission-generator', function () {
            return new Commands\PermissionGenerator;
        });
    }
}
