<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSegment extends Model
{
     protected $fillable = [
        'booking_id',
        'from',
        'to',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
        'airline',
        'operating_airline',
        'flight_number',
        'aircraft',
        'terminal',
        'cabin',
        'baggage',
        'carry_on',
        'ticket_number',
    ];
}
