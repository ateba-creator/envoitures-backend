<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Booking;
use App\Models\IDCard;
use App\Models\License;
use App\Models\Ride;
use App\Models\User;
use App\Models\Vehicle;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>User::factory(),
            'bookedBy'=>User::factory(),
            'suggestedPrice'=>fake()->numberBetween(20,1000),
            'ride_id'=>Ride::factory(),
            'validatedAt'=>fake()->dateTimeThisDecade(),
            'payment'=>fake()->randomElement(['paid','pending','rejected','cancelled']),
            'paidAt'=>fake()->dateTimeThisDecade(),
            'fee'=>fake()->numberBetween(20,1000),
            'isValidated'=>fake()->randomElement([0,1]),
            'status'=>fake()->randomElement(['paid','pending','rejected','cancelled'])
        ];
    }
}
