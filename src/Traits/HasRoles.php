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
     * @param  string $role
     *
     * @return Model
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            $this->roles()->getRelated()->whereName($role)->firstOrFail()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Model $permission
     *
     * @return bool
     */
    public function hasPermission(Model $permission)
    {
        if (property_exists($permission, 'roles')) {
            return $this->hasRole($permission->roles);
        }

        return false;
    }
}
