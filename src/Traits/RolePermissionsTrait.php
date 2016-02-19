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
     * A role may inherit various other roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inheritedRoles()
    {
        return $this->belongsToMany(self::class, 'roles_inherit', 'role_id', 'parent_id');
    }

    /**
     * Returns the current roles inherited permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function inheritedPermissions()
    {
        $roles = $this->inheritedRoles;

        return $roles->map(function ($role) {
            return $role->permissions;
        });
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
