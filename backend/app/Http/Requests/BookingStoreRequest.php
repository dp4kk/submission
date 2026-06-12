<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
               'language'                          => 'required|in:en,de',
            'passengerName'                     => 'required|string|max:255',
            'passengerEmail'                    => 'required|email',
            'bookingReference'                  => 'required|string|max:50|unique:bookings,booking_reference', 
            'flightSegments'                    => 'required|array|min:1',
            'flightSegments.*.from'             => 'required|string|max:10',
            'flightSegments.*.to'               => 'required|string|max:10',
            'flightSegments.*.departureDate'    => 'required|date',
            'flightSegments.*.departureTime'    => 'required|date_format:H:i',
            'flightSegments.*.arrivalDate'      => 'required|date',
            'flightSegments.*.arrivalTime'      => 'required|date_format:H:i',
            'flightSegments.*.airline'          => 'required|string|max:255',
            'flightSegments.*.operatingAirline' => 'required|string|max:255',
            'flightSegments.*.flightNumber'     => 'required|string|max:50',
            'flightSegments.*.aircraft'         => 'required|string|max:255',
            'flightSegments.*.terminal'         => 'nullable|string|max:100',
            'flightSegments.*.cabin'            => 'required|string|max:100',
            'flightSegments.*.baggage'          => 'required|string|max:100',
            'flightSegments.*.carryOn'          => 'required|string|max:100',
            'flightSegments.*.ticketNumber'     => 'required|string|max:100',
        ];
    }
}
