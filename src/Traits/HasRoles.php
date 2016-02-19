<?php

namespace Stevebauman\Authorization\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasRoles
{
    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $model = config('authorization.role');

        return $this->belongsToMany($model);
    }

    /**
     * Assign the given role to the user.
     *
     * @param string|Model $role
     *
     * @return Model
     */
    public function assignRole($role)
    {
        if (!$role instanceof Model) {
            $role = $this->roles()->getRelated()->whereName($role)->firstOrFail();
        }

        return $this->roles()->save($role);
    }

    /**
     * Determine if the user has the given role.
     *
     * @param mixed $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $this->roles->contains($role);
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Model $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (!$permission instanceof Model) {
            $model = config('authorization.permission');

            $permission = (new $model)->whereName($permission)->firstOrFail();
        }

        if (property_exists($permission, 'roles')) {
            return $this->hasRole($permission->roles);
        }

        return false;
    }
}
