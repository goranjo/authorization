<?php

namespace Stevebauman\Authorization\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait UserRolesTrait
{
    use HasRolesTrait;

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
     * @param string|Model|Collection $roles
     *
     * @return bool
     */
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        } else if ($roles instanceof Collection) {
            return $roles->contains(function ($key, $role) {
                return $this->roles->contains($role);
            });
        }

        return $this->roles->contains($roles);
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
            // If we weren't given a permission model, we'll try to find it by name.
            $model = config('authorization.permission');

            $permission = (new $model)->whereName($permission)->first();
        }

        // Check if we have a model instance before asking for the permissions roles.
        if ($permission instanceof Model) {
            return $this->hasRole($permission->roles);
        }

        return false;
    }
}
