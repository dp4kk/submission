<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
     protected $fillable = [
        'booking_reference',
        'passenger_name',
        'passenger_email',
        'language',
    ];

    public function flightSegments()
    {
        return $this->hasMany(FlightSegment::class);
    }
}
