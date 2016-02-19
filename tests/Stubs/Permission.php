<?php

namespace Stevebauman\Authorization\Tests\Stubs;

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

    protected $fillable = [
        'name',
        'label',
    ];
}
