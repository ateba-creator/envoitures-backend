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

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ride_id',
        'isPrivate',
        'note',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
