<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Mail\BookingItineraryMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;   
use Illuminate\Support\Facades\Storage;

class SendBookingItineraryJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    
    public function __construct(public readonly Booking $booking)
    {
        //
    }

    
    public function handle(): void
    {
        //
        $this->booking->loadMissing('flightSegments'); 

        $translations = require app_path('Translations/itinerary.php'); 
        $t = $translations[$this->booking->language] ?? $translations['en']; 

        $pdfContent = Pdf::loadView('pdf.itinerary', [
            'booking' => $this->booking, 
            't' =>$t,
            ])
            ->setPaper('a4', 'portrait')
            ->output();

            $filename = 'itinerary_' . $this->booking->booking_reference . '.pdf';


              $tempPath = 'temp/itineraries/' . $filename;
        Storage::put($tempPath, $pdfContent);

        try{
        Mail::to($this->booking->passenger_email)
            ->send(new BookingItineraryMail($this->booking, $tempPath, $filename));
        }
        finally {
            Storage::delete($tempPath);
        }
    }


    public function failed(\Throwable $e):void 
    {
                \Log::error("SendBookingItineraryJob failed for booking {$this->booking->id}: {$e->getMessage()}");

    }


}
