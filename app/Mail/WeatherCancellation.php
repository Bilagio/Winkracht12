<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeatherCancellation extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $windForce;
    public $additionalNotes;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, $windForce, $additionalNotes = null)
    {
        $this->reservation = $reservation;
        $this->windForce = $windForce;
        $this->additionalNotes = $additionalNotes;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Important: Your Kitesurfing Lesson Has Been Cancelled Due to Weather Conditions')
                    ->markdown('emails.lessons.weather-cancellation');
    }
}
