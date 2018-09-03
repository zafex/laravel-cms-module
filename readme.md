


standard response rest based on https://developers.getbase.com

**Requirement :**
- "laravel/framework": "5.6.*"

**Dependencies :**
- "tymon/jwt-auth": "dev-develop"
- "lcobucci/jwt": "^3.2"
- "ramsey/uuid": "^3.8"
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/zafex/apilaracms"
        }
    ]
```
```bash
composer require zafex/apiexlara
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
- audit
- audit_detail
- privilege
- privilege_user
- privilege_assignment
- user
- user_info
- user_permission
- setting
- menu
- menu_item

**Generate permissions base on route's name**
```
php artisan apiex:generate-permissions
```

**Generate role for admin (all permissions)**
```
php artisan apiex:generate-admin
```

**Create user**
```
php artisan apiex:create-user
```

-------------------------------------------------------------------------

## Actions
**Apiex\Actions\Auth\Authentication**

create Controller

```php
<?php

namespace App\Http\Controllers;

use Apiex\Actions\Auth;

class AuthController extends Controller
{
    // trait Auth\Authentication has one method authenticate(for create jwt token)
    use Auth\Authentication;
}
```
create Route
```php
// route name is required
Route::post('/login', 'UserController@authenticate')->name('auth.user.login');
Route::post('/register', 'UserController@register')->name('auth.user.register');
```

**Apiex\Actions\User\Information**

trait User\Information has two method:
- detail (for get detail information from current user login)
- update (for update detail information on current user login)

create Controller
```php
<?php

namespace App\Http\Controllers;

use Apiex\Actions\User;

class MeController extends Controller
{
    use User\Information;
}
```
create Route
```php
// route name is required
// use middleware api.token for verify jwt token and user permission
Route::get('/me', 'MeController@detail')->middleware('api.token')->name('me.detail');
Route::post('/me/update', [
    'as' => 'me.update',
    'uses' => 'MeController@update',
    'middleware' => [
        'api.token'
    ]
]);
```

More Actions..


**Apiex\Actions\Audit\LogList**
- index (for listing logs)

**Apiex\Actions\Audit\LogDetail**
- detail (for individual detail logs)

**Apiex\Actions\User\MemberList**
- index (for user list)

**Apiex\Actions\User\MemberCreate**
- create (for create user)

**Apiex\Actions\User\MemberDetail**
- detail (for individual detail user)

**Apiex\Actions\User\MemberUpdate**
- update (for update individual user)

**Apiex\Actions\User\MemberDelete**
- delete (for change status user to inactive or 0)

## Responses

**ResponseSingular**

for send response as singular or like detail page.
```php
public function detail(Request $request)
{
    return app('ResponseSingular')->setItem(['name' => 'dor'])->send(200);
}
```

**ResponseCollection**

for send response as collection or like index page
```php
public function index(Request $request)
{
    return app('ResponseCollection')
        ->addCollection(['name' => 'dor-1'])
        ->addCollection(['name' => 'dor-1'])
        ->send();
}
```

**ResponseError**

for send response error as collection.

```php
public function exception()
{
    $e = new Exception('this is error exception');
    return app('ResponseError')->withException($e)->send();
}
public function message()
{
    return app('ResponseError')->withMessage('this is error message')->send();
}
public function validation()
{
    if ($validator->fails()) {
        return app('ResponseError')->withValidation($validator)->send();
    }
}
``` 