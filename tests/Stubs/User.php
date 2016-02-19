<?php

namespace Stevebauman\Authorization\Tests\Stubs;

use Illuminate\Foundation\Auth\User as BaseUser;
use Stevebauman\Authorization\Traits\HasRoles;
use Stevebauman\Authorization\Traits\UserRolesTrait;

class User extends BaseUser
{
    use UserRolesTrait;

    protected $fillable = [
        'name',
    ];
}
