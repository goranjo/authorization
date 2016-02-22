# Authorization

[![Build Status](https://img.shields.io/travis/stevebauman/authorization/master.svg?style=flat-square)](https://travis-ci.org/stevebauman/authorization)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/stevebauman/authorization/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevebauman/authorization/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/stevebauman/authorization.svg?style=flat-square)](https://packagist.org/packages/stevebauman/authorization)
[![Latest Stable Version](https://img.shields.io/packagist/v/stevebauman/authorization.svg?style=flat-square)](https://packagist.org/packages/stevebauman/authorization)
[![License](https://img.shields.io/packagist/l/stevebauman/authorization.svg?style=flat-square)](https://packagist.org/packages/stevebauman/authorization)

An easy, native role / permission management system for Laravel.

Authorization automatically adds your database permissions and roles to the `Illuminate\Auth\Access\Gate`, this means
that you can utilize native laravel policies and methods for authorization.

## Installation

Insert Authorization in your `composer.json` file:

```json
"stevebauman/authorization": "1.0.*"
```

Then run `composer update`.

Insert the service provider in your `config/app.php` file:

```php
Stevebauman\Authorization\AuthorizationServiceProvider::class,
```

Once that's complete, publish the migrations using:

```php
php artisan vendor:publish --tag="authorization"
```

Then run `php artisan migrate`.

Once you've done the migrations, create the following two models and insert the relevant trait:

The Role model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Authorization\Traits\RolePermissionsTrait;

class Role extends Model
{
    use RolePermissionsTrait;

    /**
     * The roles table.
     *
     * @var string
     */
    protected $table = 'roles';
}
```

The permission model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Authorization\Traits\PermissionRolesTrait;

class Permission extends Model
{
    use PermissionRolesTrait;

    /**
     * The permissions table.
     *
     * @var string
     */
    protected $table = 'permissions';
}
```

Now insert the `Stevebauman\Authorization\Traits\UserRolesTrait` onto your `App\Models\User` model:

```php
namespace App\Models;

use Stevebauman\Authorization\Traits\UserRolesTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model
{
    use Authenticatable, Authorizable, CanResetPassword, UserRolesTrait;
    
    /**
     * The users table.
     *
     * @var string
     */
    protected $table = 'users';
}
```

You're all set!

## Usage

Create a permission:

```php
$createUsers = new Permission();

$createUsers->name = 'users.create';
$createUsers->label = 'Create Users';

$createUsers->save();
```

Grant the permission to a role:

```php
$administrator = new Role();

$administrator->name = 'administrator';
$administrator->label = 'Admin';

$administrator->save();

$administrator->grant($createUsers);
```

Now assign the role to the user:

```php
auth()->user()->assignRole($administrator);
```

Perform authorization like so:

```php
if (auth()->user()->hasPermission('users.create')) {
    
}
```

Or using Laravel's native authorization methods such as the `Gate` facade:

```php
if (Gate::allows('users.edit')) {
    //
}
```

Or by using Laravel's native `AuthorizesRequests` trait methods in your controllers:

```php
public function index()
{
    $this->authorize('users.index');
    
    // User can access index.
}
```

Or by using Laravel's native `can` method on the user:

```php
if (auth()->user()->can('users.index')) {
    // This user can access the index.
}
```


Or by using Laravel's native `@can` directive in your views:

```php
@can('users.index')
    <!-- This user can access the index. -->
@endcan
```

Checking for multiple permissions:

```php
if (auth()->user()->hasPermissions(['users.create', 'users.edit'])) {
    // This user has both creation and edit rights.
} else {
    // It looks like the user doesn't have one of the specified permissions.
}
```

Checking if the user has any permissions:

```php
if (auth()->user()->hasAnyPermissions(['users.create', 'users.edit', 'users.destroy'])) {
    // This user either has create, edit or destroy permissions.
} else {
    // It looks like the user doesn't have any of the specified permissions.
}
```

Checking if the user has a role:

```php
if (auth()->user()->hasRole('administrator')) {
    // This user is an administrator.
} else {
    // It looks like the user isn't an administrator.
}
```

Checking if the user has specified roles:

```php
if (auth()->user()->hasRoles(['administrator', 'member'])) {
    // This user is an administrator and a member.
} else {
    // It looks like the user isn't an administrator or member.
}
```

Checking if the user has any specified roles:

```php
if (auth()->user()->hasAnyRoles(['administrator', 'member', 'guest'])) {
    // This user is either an administrator, member or guest.
} else {
    // It looks like the user doesn't have any of these roles.
}
```
