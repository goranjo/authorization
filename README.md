# Authorization

[![Build Status](https://img.shields.io/travis/stevebauman/authorization/master.svg?style=flat-square)](https://travis-ci.org/stevebauman/authorization)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/stevebauman/authorization/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevebauman/authorization/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/stevebauman/authorization.svg?style=flat-square)](https://packagist.org/packages/stevebauman/authorization)
[![Latest Stable Version](https://img.shields.io/packagist/v/stevebauman/authorization.svg?style=flat-square)](https://packagist.org/packages/stevebauman/authorization)
[![License](https://img.shields.io/packagist/l/stevebauman/authorization.svg?style=flat-square)](https://packagist.org/packages/stevebauman/authorization)

An easy, native role / permission management system for Laravel.


## Installation

Insert Authorization in your `composer.json` file:

```json
"stevebauman/authorization": "1.0.*"
```

Then run `composer update`.

Once that's complete, publish the migrations using:

```php
php artisan vendor:publish --tag="authorization"
```

Then run `php artisan migrate`.

Once you've done the migrations, create two models and insert the relevant trait:

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
