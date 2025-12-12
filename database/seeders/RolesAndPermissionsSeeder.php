<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage nodes']);
        Permission::create(['name' => 'manage atlas']);
        Permission::create(['name' => 'manage labs']); // Moderate
        Permission::create(['name' => 'participate labs']); // Post
        Permission::create(['name' => 'run simulation']);
        Permission::create(['name' => 'view dashboard']);

        // create roles and assign created permissions

        // Student
        $role = Role::create(['name' => 'student']);
        $role->givePermissionTo('participate labs');
        $role->givePermissionTo('run simulation');
        $role->givePermissionTo('view dashboard');

        // Node Admin
        $role = Role::create(['name' => 'node_admin']);
        $role->givePermissionTo('manage nodes'); // Own node only (logic in policy)
        $role->givePermissionTo('manage atlas');
        $role->givePermissionTo('manage labs');
        $role->givePermissionTo('participate labs');
        $role->givePermissionTo('run simulation');
        $role->givePermissionTo('view dashboard');

        // Global Admin
        $role = Role::create(['name' => 'global_admin']);
        $role->givePermissionTo(Permission::all());
    }
}
