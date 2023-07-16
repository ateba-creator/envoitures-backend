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
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ride>
 */
class RideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start'=>fake()->dateTimeThisDecade(),
            'end'=>fake()->dateTimeThisDecade(),
            'price'=>fake()->numberBetween(20,500),
            'startAt'=>fake()->dateTimeThisDecade(),
            'user_id'=>User::factory(),
            'status'=>fake()->randomElement([0,1]),
            'type'=>fake()->randomElement([0,1]),
            'placesNumber'=>fake()->numberBetween(1,8),
            'passengerNumber'=>fake()->numberBetween(1,8),
            'twoPlaces'=>fake()->randomElement([0,1]),
            'acceptAuctions'=>fake()->randomElement([0,1]),
            'isDetourAllowed'=>fake()->randomElement([0,1]),
            'canBook'=>fake()->randomElement([0,1]),
            'views'=>fake()->numberBetween(0,1000),
        ];
    }
}
