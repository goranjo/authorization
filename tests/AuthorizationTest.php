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

        $this->assertCount(1, $user->roles);
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
        $user->assignRole('member');

        $this->assertCount(2, $user->roles);
    }

    public function test_assign_roles_with_model()
    {
        $admin = $this->createRole([
            'name' => 'administrator',
            'label' => 'Admin',
        ]);

        $member = $this->createRole([
            'name' => 'member',
            'label' => 'Member',
        ]);

        $user = $this->createUser([
            'name' => 'John Doe',
        ]);

        $user->assignRole($admin);
        $user->assignRole($member);

        $this->assertCount(2, $user->roles);
    }

    public function test_has_role()
    {
        $admin = $this->createRole([
            'name' => 'administrator',
            'label' => 'Admin',
        ]);

        $user = $this->createUser([
            'name' => 'John Doe',
        ]);

        $user->assignRole($admin);

        $this->assertTrue($user->hasRole($admin));
        $this->assertTrue($user->hasRole('administrator'));
        $this->assertFalse($user->hasRole('non-existent'));
    }
}
