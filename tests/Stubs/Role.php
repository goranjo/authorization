<?php

namespace Stevebauman\Authorization\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The roles table.
     *
     * @var string
     */
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'label',
    ];
}
