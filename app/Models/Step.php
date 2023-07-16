<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'ride_id'
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
