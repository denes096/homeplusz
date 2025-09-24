<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view property']);
        Permission::create(['name' => 'create property']);
        Permission::create(['name' => 'update own property']);
        Permission::create(['name' => 'update property']);
        Permission::create(['name' => 'request update property']);
        Permission::create(['name' => 'approve update property']);
        Permission::create(['name' => 'delete property']);

        Permission::create(['name' => 'view Labels']);
        Permission::create(['name' => 'update Labels']);
        Permission::create(['name' => 'create Labels']);
        Permission::create(['name' => 'delete Labels']);

        Permission::create(['name' => 'view Settlements']);
        Permission::create(['name' => 'update Settlements']);
        Permission::create(['name' => 'create Settlements']);
        Permission::create(['name' => 'delete Settlements']);

        Permission::create(['name' => 'view client']);
        Permission::create(['name' => 'create client']);
        Permission::create(['name' => 'update own client']);
        Permission::create(['name' => 'update client']);
        Permission::create(['name' => 'delete client']);

        Permission::create(['name' => 'assign seller to property']);
        Permission::create(['name' => 'assign seller role']);
        Permission::create(['name' => 'revoke seller role']);

        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'update articles']);
        Permission::create(['name' => 'delete articles']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create roles and assign created permissions

        $referensRole = Role::create(['name' => 'referens'])
            ->givePermissionTo('create property')
            ->givePermissionTo('update own property')
            ->givePermissionTo('request update property')
            ->givePermissionTo('create client');
        $referensRole->save();

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('create property')
            ->givePermissionTo('update own property')
            ->givePermissionTo('request update property')
            ->givePermissionTo('approve update property')
            ->givePermissionTo('delete property')

            ->givePermissionTo('create client')
            ->givePermissionTo('update own client')
            ->givePermissionTo('update client')
            ->givePermissionTo('delete client')

            ->givePermissionTo('view Settlements')
            ->givePermissionTo('create Settlements')
            ->givePermissionTo('update Settlements')
            ->givePermissionTo('delete Settlements')

            ->givePermissionTo('assign seller to property')
            ->givePermissionTo('assign seller role')
            ->givePermissionTo('revoke seller role')

            ->givePermissionTo('create articles')
            ->givePermissionTo('update articles')
            ->givePermissionTo('delete articles');
        $adminRole->save();

        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());
        $superAdminRole->save();

        User::find(1)->assignRole('super-admin');
        User::find(2)->assignRole('admin');
        User::find(3)->assignRole('referens');
    }
}
