<?php

namespace Stevebauman\Authorization\Traits;

use Illuminate\Database\Eloquent\Model;

trait RolePermissionsTrait
{
    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        $model = config('authorization.permission');

        return $this->belongsToMany($model);
    }

    /**
     * Grant the given permission to a role.
     *
     * @param  Model $permission
     *
     * @return Model
     */
    public function grant(Model $permission)
    {
        return $this->permissions()->save($permission);
    }
}
