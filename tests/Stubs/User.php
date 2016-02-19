<?php

namespace Stevebauman\Authorization\Tests\Stubs;

use Illuminate\Foundation\Auth\User as BaseUser;
use Stevebauman\Authorization\Traits\HasRoles;

class User extends BaseUser
{
    use HasRoles;

    protected $fillable = [
        'name',
    ];
}
