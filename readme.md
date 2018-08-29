

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

-------------------------------------------------------------------------

## Actions
**Apiex\Actions\Auth\Authentication && Apiex\Actions\Auth\Registration**
create Controller
```php
<?php

namespace App\Http\Controllers;

use Apiex\Actions\Auth;

class AuthController extends Controller
{
	// trait Auth\Authentication has one method authenticate(for create jwt token)
	use Auth\Authentication;

	// trait Auth\Registration has one method register (for user signup)
	use Auth\Registration;
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
Route::post('/me/update', 'MeController@update')->middleware('api.token')->name('me.update');
```

More Actions..


**Apiex\Actions\Audit\LogList**
- index (for listing logs)

**Apiex\Actions\Audit\LogDetail**
- detail (for individual detail logs)

**Apiex\Actions\User\MemberList**
- index (for user list)

**Apiex\Actions\User\MemberDetail**
- detail (for individual detail user)

**Apiex\Actions\User\MemberUpdate**
- update (for update individual user)

**Apiex\Actions\User\MemberDelete**
- delete (for change status user to inactive or 0)