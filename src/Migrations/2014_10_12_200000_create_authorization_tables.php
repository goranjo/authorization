<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The roles table.
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // The name field is mandatory.
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // The inheritable roles table.
        Schema::create('roles_inherit', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('parent_id')->unsigned();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'parent_id']);
        });

        // The permissions table.
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // The name field is mandatory.
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // The role permissions pivot table.
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        // The role user pivot table.
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('roles_inherit');
        Schema::drop('roles');
    }
}
