
standard response rest based on https://developers.getbase.com

**Requirement :**
- "laravel/framework": "5.6.*"

**Dependencies :**
- "tymon/jwt-auth": "dev-develop"
- "lcobucci/jwt": "^3.2"
- "ramsey/uuid": "^3.8"
```bash
composer require zafex/lara-apicms
php artisan vendor:publish --provider="Apiex\ApiexServiceProvider"
php artisan jwt:secret
```

    note: Apiex\ApiexServiceProvider is extended from Tymon\JWTAuth\Providers\LaravelServiceProvider for create config/jwt.php

add `'api.token' => \Apiex\Middleware\TokenAuthorization::class` to routeMiddleware on Kernel.php

**Migrating Schema**
```
php artisan migrate
```
make sure your migrations folder is not contains :
- user
- user_info
- privilege
- privilege_user
- privilege_assignment
- user_permission
- audit
- audit_detail
- menu

**Generate permissions base on route's name**
```
php artisan apiex:generate-permissions
```

**Generate role for admin (all permissions)**
```
php artisan apiex:generate-role-admin
```

**Create user for admin**
```
php artisan apiex:create-admin
```