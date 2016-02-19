<?php

namespace Stevebauman\Authorization\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The permissions table.
     *
     * @var string
     */
    protected $table = 'permissions';
}
