<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new \FakerRestaurant\Provider\id_ID\Restaurant($faker));

        for ($i = 0; $i < 30; $i++) {
            $data = DB::table('menu')->insert([
                'vendor_id' => $faker->numberBetween(1, 10),
                'menu_name' => $faker->foodName(),
                'description' => $faker->paragraph,
                'image' => 'https://source.unsplash.com/random/640x640/?makanan,food,catering,meal',
                'type' => $faker->randomElement(['spicy', 'no_spicy']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            for ($j = 0; $j < 3; $j++) {
                $size = $faker->randomElement(['Small', 'Medium', 'Large']);

                $existingCombination = DB::table('menu_detail')
                    ->where('menu_id', $i + 1)
                    ->where('size', $size)
                    ->exists();

                while ($existingCombination) {
                    $size = $faker->randomElement(['Small', 'Medium', 'Large']);
                    $existingCombination = DB::table('menu_detail')
                        ->where('menu_id', $i + 1)
                        ->where('size', $size)
                        ->exists();
                }

                $data = DB::table('menu_detail')->insert([
                    'menu_id' => $i + 1,
                    'size' => $size,
                    'price' => $faker->randomElement([10000, 20000, 30000, 40000, 50000]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
