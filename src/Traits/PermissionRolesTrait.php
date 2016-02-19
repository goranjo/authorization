<?php

namespace Stevebauman\Authorization\Traits;

trait PermissionRolesTrait
{
    use HasRolesTrait;

    /**
     * Returns all roles, including inherited roles.
     *
     * @return mixed
     */
    public function allRoles()
    {
        $roles = $this->roles;

        $inherited = $roles->map(function ($role) {
            return $role->inheritedRoles;
        });

        foreach ($inherited as $key => $inheritedRoles) {
            $roles = $roles->merge($inheritedRoles);
        }

        return $roles;
    }
}
