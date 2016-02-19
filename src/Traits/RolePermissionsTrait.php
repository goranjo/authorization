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

        $permissions = $roles->map(function ($role) {
            return $role->permissions;
        });

        $collection = new Collection();

        foreach ($permissions as $key => $permission) {
            $collection = $collection->merge($permission);
        }

        return $collection;
    }

    /**
     * Returns all permissions (including inherited).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allPermissions()
    {
        $permissions = $this->permissions;

        $inherited = $this->inheritedPermissions();

        $permissions = $permissions->merge($inherited);

        return $permissions;
    }

    /**
     * Inherits the specified roles permissions.
     *
     * @param Model $role
     *
     * @return Model
     */
    public function inheritRole($role)
    {
        return $this->inheritedRoles()->save($role);
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
