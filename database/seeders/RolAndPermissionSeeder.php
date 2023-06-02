<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolAndPermissionSeeder extends Seeder
{
    public function run()
    {
        
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $createPostPermission = Permission::create(['name' => 'create post']);
        $editPostPermission = Permission::create(['name' => 'edit post']);
        
      
        $adminRole->givePermissionTo($createPostPermission, $editPostPermission);
        $userRole->givePermissionTo($createPostPermission);
    }
}
