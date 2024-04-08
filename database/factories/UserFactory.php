<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('id_ID');

        $userLatitude = -7.2989853; // Example: User's latitude
        $userLongitude = 112.6294714; // Example: User's longitude
        $radiusInDegrees = 0.1; // Example: Roughly 50 kilometers radius

        // Generate latitude and longitude coordinates within the specified radius
        $latitude = $this->faker->latitude($userLatitude - $radiusInDegrees, $userLatitude + $radiusInDegrees);
        $longitude = $this->faker->longitude($userLongitude - $radiusInDegrees, $userLongitude + $radiusInDegrees);

        return [
            'name' => $faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'current_team_id' => null,
            'address' => $faker->address(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'about_us' => $faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 2, 3, 4, 5),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (!Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name . '\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
