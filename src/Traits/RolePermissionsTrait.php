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
     * Returns the granted permission(s).
     *
     * @param  Model|array $permissions
     *
     * @return Model|\Illuminate\Support\Collection
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

    /**
     * Revoke the given permission to a role.
     *
     * Returns the number of revoked permissions.
     *
     * @param  Model|array $permissions
     *
     * @return int
     */
    public function revoke($permissions)
    {
        if (is_array($permissions)) {
            $permissions = collect($permissions);

            return $permissions->each(function ($permission, $key) {
                $this->revoke($permission);
            })->count();
        } else {
            return $this->permissions()->detach($permissions);
        }
    }
}
