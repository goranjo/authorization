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
     * Returns true / false if the current role has the specified permission.
     *
     * @param string|Model $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            $permission = $this->permissions()->whereName($permission)->first();
        }

        if ($permission instanceof Model) {
            return $this->permissions->contains($permission);
        }

        return false;
    }

    /**
     * Returns true / false if the current role has the specified permissions.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function hasPermissions($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $permissions = collect($permissions);

        $count = $permissions->count();

        return $permissions->filter(function ($permission) {
            return $this->hasPermission($permission);
        })->count() === $count;
    }

    /**
     * Returns true / false if the current role has any of the specified permissions.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAnyPermissions($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $permissions = collect($permissions);

        return $permissions->filter(function ($permission) {
            return $this->hasPermission($permission);
        })->count() > 0;
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

    /**
     * Revokes all permissions on the current role.
     *
     * @return int
     */
    public function revokeAll()
    {
        return $this->permissions()->detach();
    }


}
