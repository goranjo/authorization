<?php

namespace Stevebauman\Authorization\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @param string|array|Model $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (!is_array($permission)) {
            $permission = [$permission];
        }

        // Collect the permissions.
        $permission = collect($permission);

        // Get a before count of all the inserted permissions.
        $count = $permission->count();

        // Filter through each permission to see if the user has the permission and
        // return true if the filtered collection count is the same.
        return $permission->filter(function ($permission, $key) {
            if (is_string($permission)) {
                // If we weren't given a permission model, we'll try to find it by name.
                $model = config('authorization.permission');

                $permission = (new $model)->whereName($permission)->first();
            }

            if ($permission instanceof Model) {
                return $this->hasRole($permission->allRoles());
            }

            return false;
        })->count() === $count;
    }

    /**
     * Returns true / false if the user does not have the specified permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function doesNotHavePermission($permission)
    {
        return ! $this->hasPermission($permission);
    }
}
