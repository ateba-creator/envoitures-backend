<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
        ->count(3)
        ->hasVehicle()
        ->hasReviews(3)
        ->hasBookings(3)
        ->create();

        User::factory()
        ->count(3)
        ->create();

        User::factory()
        ->count(3)
        ->create();

        User::factory()
        ->count(3)
        ->create();

        User::factory()
        ->count(3)
        ->hasVehicle()
        ->create();

        User::factory()
        ->count(3)
        ->hasVehicle()
        ->hasReviews()
        ->create();
        
        User::factory()
        ->count(3)
        ->hasVehicle()
        ->hasReviews(3)
        ->hasBookings(5)
        ->create();
    }
}
