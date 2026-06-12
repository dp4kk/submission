<?php

namespace App\Http\Controllers;

use App\Services\BookingService; 
use App\Http\Requests\BookingStoreRequest; 

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookingService){

    }

    public function store(BookingStoreRequest $request){
        $result = $this->bookingService->processBooking($request->validated()); 

        return response()->json([
        'message' => $result['message'], 
        'booking_id' => $result['booking']->id,
        'booking_reference' => $result['booking']->booking_reference
        ],201);
    }

}