<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\IDCard;
use App\Models\License;
use App\Models\Ride;
use App\Models\User;
use App\Models\Vehicle;


class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start',
        'end',
        'price',
        'startAt',
        'status',
        'placesNumber',
        "passengerNumber",
        'twoPlaces',
        'acceptAuctions',
        'isDetourAllowed',
        'isFood',
        'isBagAllowed',
        'isMusicAllowed',
        'isAnimalAllowed',
        'canBook',
        'views'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
}   
