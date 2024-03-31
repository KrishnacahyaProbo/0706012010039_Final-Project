<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

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
    }
}
