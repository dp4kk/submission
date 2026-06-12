<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class BookingItineraryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $t;

    public function __construct(
        public readonly Booking $booking,
        
        public readonly string $pdfPath,
        public readonly string $filename,
    ) {
        $translations = require app_path('Translations/itinerary.php');
        $raw = $translations[$booking->language] ?? $translations['en'];

        $this->t = array_merge($raw, [
            'email_subject'  => str_replace(':ref',  $booking->booking_reference, $raw['email_subject']),
            'email_greeting' => str_replace(':name', $booking->passenger_name,    $raw['email_greeting']),
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->t['email_subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-itinerary',
            with: [
                'booking' => $this->booking,
                't'       => $this->t,
            ],
        );
    }

    public function attachments(): array
    {
        return [
           
            Attachment::fromData(
                fn () => Storage::get($this->pdfPath),
                $this->filename
            )->withMime('application/pdf'),
        ];
    }
}