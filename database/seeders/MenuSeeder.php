<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 30; $i++) {
            $data = DB::table('menu')->insert([
                'vendor_id' => $faker->numberBetween(1, 10),
                'menu_name' => $faker->sentence(2),
                'description' => $faker->paragraph,
                'image' => $faker->imageUrl(),
                'type' => $faker->randomElement(['spicy', 'no_spicy']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            for ($j = 0; $j < 5; $j++) {
                $data = DB::table('menu_detail')->insert([
                    'menu_id' => $i + 1,
                    'size' => $faker->randomElement(['Small', 'Medium', 'Large']),
                    'price' => $faker->numberBetween(10000, 50000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
