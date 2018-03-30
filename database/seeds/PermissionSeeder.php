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

        // general permissions
        Permission::findOrCreate('load app');

        // content management permissions
        Permission::findOrCreate('create articles');
        Permission::findOrCreate('view articles');
        Permission::findOrCreate('edit articles');
        Permission::findOrCreate('delete articles');
        Permission::findOrCreate('manage articles');
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
        $role->syncPermissions(['view articles', 'edit articles', 'delete articles', 'create articles', 'load app']);

        $role = $this->makeRole('editor');
        $role->syncPermissions(['view articles', 'publish articles', 'retire articles', 'review articles', 'review own articles', 'load app']);

        $role = $this->makeRole('administrator');
        $role->syncPermissions(['manage users', 'manage permissions', 'load app']);

        $role = $this->makeRole('end user');
        $role->syncPermissions(['search articles', 'read articles', 'load app']);

        $role = $this->makeRole('account holder');
        $role->syncPermissions(['manage own billing', 'manage own users', 'load app']);
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
