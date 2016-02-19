<?php

namespace Stevebauman\Authorization\Tests;

use Stevebauman\Authorization\Tests\Stubs\Permission;
use Stevebauman\Authorization\Tests\Stubs\Role;
use Stevebauman\Authorization\Tests\Stubs\User;

class AuthorizationTest extends TestCase
{
    /**
     * Returns a new user instance.
     *
     * @param array $attributes
     *
     * @return User
     */
    protected function createUser($attributes = [])
    {
        return User::create($attributes);
    }

    /**
     * Returns a new role instance.
     *
     * @param array $attributes
     *
     * @return Role
     */
    protected function createRole($attributes = [])
    {
        return Role::create($attributes);
    }

    /**
     * Returns a new permission instance.
     *
     * @param array $attributes
     *
     * @return Permission
     */
    protected function createPermission($attributes = [])
    {
        return Permission::create($attributes);
    }

    public function test_assign_role()
    {
        $this->createRole([
            'name' => 'administrator',
            'label' => 'Admin',
        ]);

        $user = $this->createUser([
            'name' => 'John Doe',
        ]);

        $user->assignRole('administrator');
    }

    public function test_assign_multiple_roles()
    {
        $this->createRole([
            'name' => 'administrator',
            'label' => 'Admin',
        ]);

        $this->createRole([
            'name' => 'member',
            'label' => 'Member',
        ]);

        $user = $this->createUser([
            'name' => 'John Doe',
        ]);

        $user->assignRole('administrator');
    }
}
