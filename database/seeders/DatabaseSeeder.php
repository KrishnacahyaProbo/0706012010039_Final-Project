<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
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
            'name' => 'Cahya Catering',
            'email' => 'cahyacatering@example.com',
            'password' => bcrypt('password'),
        ]);

        $vendorUser->assignRole($vendorRole);

        $customerUser = User::factory()->create([
            'name' => 'Probo Krishnacahya',
            'email' => 'pkrishnacahya@example.com',
            'password' => bcrypt('password'),
        ]);

        $customerUser->assignRole($customerRole);

        // Create users
        User::factory()->count(30)->create()->each(function ($user) use ($vendorRole) {
            $user->assignRole($vendorRole);
            if ($user->hasRole('vendor')) {
                $faker = Faker::create('id_ID');
                $user->update(['name' => $faker->company()]);
            }
        });
        User::factory()->count(10)->create()->each(function ($user) use ($customerRole) {
            $user->assignRole($customerRole);
        });

        $this->call([
            MenuSeeder::class,
        ]);
    }
}
