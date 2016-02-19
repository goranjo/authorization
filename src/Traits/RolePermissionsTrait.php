<?php

namespace Stevebauman\Authorization\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
     * @return Collection
     */
    public function grant($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $permissions = collect($permissions);

        return $permissions->filter(function ($permission, $key) {
            if ($permission instanceof Model) {
                return $this->permissions()->save($permission) instanceof Model;
            }

            return false;
        });
    }

    /**
     * Revoke the given permission to a role.
     *
     * Returns a collection of revoked permissions.
     *
     * @param  Model|array $permissions
     *
     * @return Collection
     */
    public function revoke($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $permissions = collect($permissions);

        return $permissions->filter(function ($permission, $key) {
            return $this->permissions()->detach($permission) === 1;
        });
    }
}
