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
     * @param  Model|array $permissions
     *
     * @return Model
     */
    public function grant($permissions)
    {
        if (is_array($permissions)) {
            $permissions = collect($permissions);

            return $permissions->each(function ($permission, $key) {
                $this->permissions()->save($permission);
            });
        } else {
            return $this->permissions()->save($permissions);
        }
    }
}
