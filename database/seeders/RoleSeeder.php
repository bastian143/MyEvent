<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $userUploader = Role::firstOrCreate(['name' => 'user uploader']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Create initial admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@myevent.com'],
            [
                'name' => 'Admin MyEvent',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->assignRole($admin);
        
        // Create initial user uploader user
        $uploaderUser = User::firstOrCreate(
            ['email' => 'uploader@myevent.com'],
            [
                'name' => 'User Uploader MyEvent',
                'password' => bcrypt('password'),
            ]
        );
        $uploaderUser->assignRole($userUploader);

        // Create initial user
        $normalUser = User::firstOrCreate(
            ['email' => 'user@myevent.com'],
            [
                'name' => 'User MyEvent',
                'password' => bcrypt('password'),
            ]
        );
        $normalUser->assignRole($user);
    }
}
