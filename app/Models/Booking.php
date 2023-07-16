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

class Booking extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'bookedBy',
        'ride_id',
        'suggestedPrice',
        'payment',
        'fee',
        'paidAt',
        'isValidated',
        'validatedAt',
        'status',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function bookers()
    {
        return $this->hasMany(User::class, 'bookedBy');
    }

}
