<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MenuDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Probo Krishnacahya',
            'email' => 'pkrishnacahya@student.ciputra.ac.id',
            'password' => bcrypt('password'),
        ]);

        // Create a role
        $vendorRole = Role::create(['name' => 'vendor']);
        $customerRole = Role::create(['name' => 'customer']);

        // Create a permission
        $manageMenuPermissionVendor = Permission::create(['name' => 'manage-menu']);
        $manageMenuPermissionCustomer = Permission::create(['name' => 'show-menu']);

        // Assign the manage-menu permission to the vendor role
        $vendorRole->givePermissionTo($manageMenuPermissionVendor);
        $customerRole->givePermissionTo($manageMenuPermissionCustomer);

        // Create 100 vendor users
        User::factory()->count(100)->create()->each(function ($user) use ($vendorRole) {
            $user->assignRole($vendorRole);
        });

        User::factory()->count(25)->create()->each(function ($user) use ($customerRole) {
            $user->assignRole($customerRole);
        });

        $this->call([
            MenuSeeder::class,
        ]);
    }
}
