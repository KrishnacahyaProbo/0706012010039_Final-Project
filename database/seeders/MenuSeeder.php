<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $faker = Faker::create();
            $faker->addProvider(new \FakerRestaurant\Provider\id_ID\Restaurant($faker));

            $menuDetailData = [];

            for ($i = 0; $i < 10; $i++) {
                $menuId = DB::table('menu')->insertGetId([
                    'vendor_id' => $faker->numberBetween(1, 10),
                    'menu_name' => $faker->foodName(),
                    'description' => $faker->paragraph(),
                    'image' => 'https://source.unsplash.com/random/640x640/?makanan,food,catering,meal',
                    'type' => $faker->randomElement(['spicy', 'no_spicy']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $sizes = ['Small', 'Medium', 'Large'];
                $prices = [
                    'Small' => $faker->randomElement([10000, 15000, 20000]),
                    'Medium' => $faker->randomElement([25000, 30000, 35000]),
                    'Large' => $faker->randomElement([40000, 45000, 50000])
                ];

                foreach ($sizes as $size) {
                    $menuDetailData[] = [
                        'menu_id' => $menuId,
                        'size' => $size,
                        'price' => $prices[$size],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            DB::table('menu_detail')->insert($menuDetailData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
