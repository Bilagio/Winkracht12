<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorIllnessCancellation extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $additionalNotes;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, $additionalNotes = null)
    {
        $this->reservation = $reservation;
        $this->additionalNotes = $additionalNotes;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Important: Your Kitesurfing Lesson Has Been Cancelled')
                    ->markdown('emails.lessons.instructor-illness-cancellation');
    }
}
