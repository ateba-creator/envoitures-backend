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
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vehicleList  = [
            'volvo','toyota','mercedes benz','audi','infinity','hyundai','peugot'
        ];

        return [
            'user_id'=>User::factory(),
            'designation'=>fake()->randomElement($vehicleList),
            'description'=>fake()->realText(),
            'imageName'=>fake()->image('public/storage/vehicleImages',640,480, null, false),
            'isMusicAllowed'=>fake()->randomElement([0,1]),
            'isAnimalAllowed'=>fake()->randomElement([0,1]),
            'isBagAllowed'=>fake()->randomElement([0,1]),
            'isFoodAllowed'=>fake()->randomElement([0,1]),
        ];
    }
}
