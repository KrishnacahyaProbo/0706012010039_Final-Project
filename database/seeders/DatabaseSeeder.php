<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a role
        $vendorRole = Role::create(['name' => 'vendor']);
        $customerRole = Role::create(['name' => 'customer']);

        // Create a user
        $vendorUser = User::factory()->create([
            'name' => 'Jessi Jon\'s Kitchen',
            'email' => 'jessijonskitchen@example.com',
            'password' => bcrypt('password'),
        ]);

        $vendorUser->assignRole($vendorRole);

        $customerUser = User::factory()->create([
            'name' => 'Probo Krishnacahya',
            'email' => 'pkrishnacahya@example.com',
            'password' => bcrypt('password'),
        ]);

        $customerUser->assignRole($customerRole);

        // Create a permission
        $manageMenuPermissionVendor = Permission::create(['name' => 'manage-menu']);
        $manageMenuPermissionCustomer = Permission::create(['name' => 'show-menu']);

        // Assign the manage-menu permission to the vendor role
        $vendorRole->givePermissionTo($manageMenuPermissionVendor);
        $customerRole->givePermissionTo($manageMenuPermissionCustomer);

        // Create users
        User::factory()->count(30)->create()->each(function ($user) use ($vendorRole) {
            $user->assignRole($vendorRole);
        });
        User::factory()->count(10)->create()->each(function ($user) use ($customerRole) {
            $user->assignRole($customerRole);
        });

        $this->call([
            MenuSeeder::class,
        ]);
    }
}
