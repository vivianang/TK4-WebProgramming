<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // Define all permissions
        $permissionNames = [
            // Permissions for Data Barang
            'item:browse',
            'item:read',
            'item:edit',
            'item:add',
            'item:delete',

            // Permissions for Pelanggan
            'customer:browse',
            'customer:read',
            'customer:edit',
            'customer:add',
            'customer:delete',

            // Permissions for User
            'staff:browse',
            'staff:read',
            'staff:edit',
            'staff:add',
            'staff:delete',

            // Permissions for Transaksi
            'transaction:browse',
            'transaction:read',
            'transaction:edit',
            'transaction:add',
            'transaction:delete',
            'transaction:approve'
        ];

        // Define roles and access to it
        $roles = [
            'admin' => $permissionNames,
            'staff' => array_filter($permissionNames, function($var) {
                // Staff cannot modify or alter user
                return !\str_starts_with($var, "staff:");
            }),
            'customer' => [
                'item:browse',
                'transaction:browse',
                'transaction:read',
                'transaction:add',
            ] // Default user can't do anything.
        ];

        // Create Super Admin User
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@tokopos.id',
            'password' => Hash::make('admintokopos'),
        ]);

        // Find newly created user
        $user = User::where('email', 'admin@tokopos.id')->first();

        // Create Staff User
        DB::table('users')->insert([
            'name' => 'Staff',
            'email' => 'staff@tokopos.id',
            'password' => Hash::make('stafftokopos'),
        ]);

        // Find newly created staff
        $userStaff = User::where('email', 'staff@tokopos.id')->first();

        // Create Staff
        $userStaffOut = DB::table('staffs')->insertGetId([
            'user_id' => $userStaff->id,
            'gender' => 'L',
        ]);
        $userStaff->staff_id = $userStaffOut;
        $userStaff->save();
    
        
        // Create Permissions
        foreach ($permissionNames as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles, and assign to permission
        foreach ($roles as $roleName => $rolePermission) {
            $newRole = Role::create(['name' => $roleName]);
            foreach ($rolePermission as $permission) {
                $_permission = Permission::where('name', '=', $permission)->first();
                $_permission->assignRole($newRole);
            }
        }
        
        // Finally, assign role
        $user->assignRole(Role::where('name', '=', 'admin')->first());
        $userStaff->assignRole(Role::where('name', '=', 'staff')->first());
    }
}
