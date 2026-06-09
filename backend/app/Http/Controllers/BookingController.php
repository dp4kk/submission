<?php

namespace App\Http\Controllers;

use App\Models\Booking; 
use App\Models\FlightSegment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Mail\BookingItineraryMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    private function generatePdf(Booking $booking): string
    {
        $translations = require app_path('Translations/itinerary.php');
        $t = $translations[$booking->language] ?? $translations['en'];

        
        $booking->load('flightSegments');

        $pdf = Pdf::loadView('pdf.itinerary', [
            'booking' => $booking,
            't'       => $t,
        ])->setPaper('a4', 'portrait');

        $filename = 'itinerary_' . $booking->booking_reference . '.pdf';
        $path = storage_path('app/public/' . $filename);
        
        
        $directory = dirname($path);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $pdf->save($path);

        return $path;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
        ]);

        
        \DB::beginTransaction();
        
        try {
            $booking = Booking::create([
                'booking_reference' => $validated['bookingReference'],
                'passenger_name'    => $validated['passengerName'],
                'passenger_email'   => $validated['passengerEmail'],
                'language'          => $validated['language'],
            ]);

            foreach ($validated['flightSegments'] as $segment) {
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

            $pdfPath = $this->generatePdf($booking);
            Mail::to($booking->passenger_email)
    ->send(new BookingItineraryMail($booking, $pdfPath));
            $pdfUrl = Storage::url('public/' . basename($pdfPath));
            
            \DB::commit();

            return response()->json([
                'message' => 'Booking created successfully',
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'pdf_url' => $pdfUrl, 
            ], 201);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to create booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}