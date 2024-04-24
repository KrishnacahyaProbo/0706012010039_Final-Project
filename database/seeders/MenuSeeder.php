<?php

namespace Database\Seeders;

use DateTime;
use App\Models\Schedule;
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

            // Get today's date
            $startDate = new DateTime();
            // Create an array to store the schedules
            $schedules = [];
            // Loop through the next 50 days
            for ($i = 0; $i < 50; $i++) {
                // Add $i days to the start date
                $scheduleDate = $startDate->modify("+{$i} day")->format('Y-m-d');
                // Create a new Schedule instance
                $schedule = new Schedule();
                $schedule->schedule = $scheduleDate;
                // Add other attributes if needed
                // Add the schedule to the array
                $schedules[] = $schedule;
            }
            // Save all schedules
            foreach ($schedules as $schedule) {
                $schedule->save();
            }

            $menuDetailData = [];

            for ($i = 0; $i < 10; $i++) {
                $menuId = DB::table('menu')->insertGetId([
                    'vendor_id' => $faker->numberBetween(1, 10),
                    'menu_name' => $faker->foodName(),
                    'description' => $faker->sentence(),
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

                $scheduleId = DB::table('schedule')->inRandomOrder()->pluck('id')->first();
                // Assuming $scheduleId holds the schedule ID
                DB::table('menu_schedule')->insert([
                    'menu_id' => $menuId,
                    'schedule_id' => $scheduleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('menu_detail')->insert($menuDetailData);
            DB::commit();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
