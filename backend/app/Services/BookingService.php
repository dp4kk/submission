<?php

namespace App\Services; 

use App\Models\Booking; 
use App\Jobs\SendBookingItineraryJob; 
use Illuminate\Support\Facades\DB; 


class BookingService
{
    public function createBooking(array $validatedData):Booking{
        return DB::transaction(function () use ($validatedData) {
            $booking = Booking::create([
                'booking_reference' => $validatedData['bookingReference'],
                'passenger_name'    => $validatedData['passengerName'],
                'passenger_email'   => $validatedData['passengerEmail'],
                'language'          => $validatedData['language'],
            ]);

            foreach ($validatedData['flightSegments'] as $segment ) {
                $booking->flightSegments()->create([
                    'from'              => $segment['from'],
                    'to'                => $segment['to'],
                    'departure_date'    => $segment['departureDate'],
                    'departure_time'    => $segment['departureTime'],
                    'arrival_date'      => $segment['arrivalDate'],
                    'arrival_time'      => $segment['arrivalTime'],
                    'airline'           => $segment['airline'],
                    'operating_airline' => $segment['operatingAirline'],
                    'flight_number'     => $segment['flightNumber'],
                    'aircraft'          => $segment['aircraft'],
                    'terminal'          => $segment['terminal'] ?? null,
                    'cabin'             => $segment['cabin'],
                    'baggage'           => $segment['baggage'],
                    'carry_on'          => $segment['carryOn'],
                    'ticket_number'     => $segment['ticketNumber'],
                ]);
            }

            return $booking;

        });
    }


    public function processBooking(array $validatedData): array
    {
        $booking = $this->createBooking($validatedData);


        SendBookingItineraryJob::dispatch($booking)->afterCommit();

        return[
            'booking' => $booking, 
            'message' => 'Booking created successfully. Itinerary will be sent via email'
        ];
    }


}