<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Booking;
use App\Models\IDCard;
use App\Models\License;
use App\Models\Ride;
use App\Models\User;
use App\Models\Vehicle;

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
        return [
            'fname' => fake()->firstName(),
            'lname' => fake()->lastName(),
            'username'=>fake()->unique()->safeEmail(),
            'birthDate' => fake()->dateTimeThisDecade(),
            'age' => fake()->numberBetween(18,50),
            'phoneNumber'=>fake()->phoneNumber(),
            'sex' => fake()->randomElement(['m','f']),
            'receivingNewsPapers' => fake()->randomElement([True,False]),
            'isAcceptedAutomatically'=> fake()->randomElement([True,False]),
            'isDetourPossible'=> fake()->randomElement([True,False]),

            'imageName' => fake()->image('public/storage/userImages',640,480, null, false),
            'licenseImageVerso' => fake()->image('public/storage/licenseImages',640,480, null, false),
            'licenseVersoUpdated' => fake()->dateTimeThisDecade(),
            'licenseImageRecto' => fake()->image('public/storage/licenseImages',640,480, null, false),
            'licenseRectoUpdated' => fake()->dateTimeThisDecade(),

            'idCardImageVerso' => fake()->image('public/storage/idCardImages',640,480, null, false),
            'idCardVersoUpdated' => fake()->dateTimeThisDecade(),
            'idCardImageRecto' => fake()->image('public/storage/idCardImages',640,480, null, false),
            'idCardRectoUpdated' => fake()->dateTimeThisDecade(),

            'isActive' => fake()->randomElement([True,False]),
            'paymentAccount' => fake()->asciify('********************'),
            'googleId' => fake()->asciify('********************'),
            'facebookId' => fake()->asciify('********************'),
            'role' => fake()->randomElement(['[ROLE_USER]','[ROLE_ADMIN]']),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
   
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
