<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // content management permissions
        Permission::findOrCreate('create articles');
        Permission::findOrCreate('edit articles');
        Permission::findOrCreate('delete articles');
        Permission::findOrCreate('publish articles');
        Permission::findOrCreate('retire articles');
        Permission::findOrCreate('review articles');
        Permission::findOrCreate('review own articles');

        // system management
        Permission::findOrCreate('manage users');
        Permission::findOrCreate('manage permissions');

        // content consumption
        Permission::findOrCreate('search articles');
        Permission::findOrCreate('read articles');
        Permission::findOrCreate('manage own billing');
        Permission::findOrCreate('manage own users');

        // create roles and assign existing permissions
        $role = $this->makeRole('author');
        $role->syncPermissions(['edit articles', 'delete articles', 'create articles']);

        $role = $this->makeRole('editor');
        $role->syncPermissions(['publish articles', 'retire articles', 'review articles', 'review own articles']);

        $role = $this->makeRole('administrator');
        $role->syncPermissions(['manage users', 'manage permissions']);

        $role = $this->makeRole('end user');
        $role->syncPermissions(['search articles', 'read articles']);

        $role = $this->makeRole('account holder');
        $role->syncPermissions(['manage own billing', 'manage own users']);
    }

    /**
     * @param $name
     * @return \Spatie\Permission\Contracts\Role
     */
    public function makeRole($name): Role
    {
        try {
            return Role::create(['name' => $name]);
        } catch (\Spatie\Permission\Exceptions\RoleAlreadyExists $e) {
            return Role::findByName($name);
        }
    }
}