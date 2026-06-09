<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingItineraryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $pdfPath;

  
    public function __construct(Booking $booking, string $pdfPath)
    {
        $this->booking = $booking;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Your Flight Itinerary')
            ->view('emails.booking-itinerary')
            ->attach($this->pdfPath, [
                'as' => 'itinerary.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}