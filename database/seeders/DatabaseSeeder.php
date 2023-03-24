<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $roleP = Role::where('name', 'Pelanggan')->first();
        if (is_null($roleP))
            Role::create(['name' => 'Pelanggan']);

        $role = Role::where('name', 'super admin')->first();
        if (is_null($role))
            $role = Role::create(['name' => 'super admin']);
        $user = User::where('username', 'admin')->where('email', 'admin@admin.com')->first();
        if (is_null($user))
            $user = User::create([
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('superadmin')
            ]);
        $user->assignRole($role);

        // Reset cached roles and permissions
        // Permission::firstOrCreate(['name' => 'create_user']);
        // Permission::create(['name' => 'view_user']);
        // Permission::create(['name' => 'edit_user']);
        // Permission::create(['name' => 'delete_user']);
    }
}
