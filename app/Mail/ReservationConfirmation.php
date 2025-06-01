<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The reservation instance.
     *
     * @var \App\Models\Reservation
     */
    public $reservation;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Windkracht 13 - Reservation Confirmation #' . $this->reservation->invoice_number,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.reservation-confirmation',
            with: [
                'reservation' => $this->reservation,
                'user' => $this->reservation->user,
                'lessonPackage' => $this->reservation->lessonPackage,
                'location' => $this->reservation->location,
                'paymentInstructions' => $this->getPaymentInstructions(),
                'bankDetails' => $this->getBankDetails(),
            ],
        );
    }

    /**
     * Get the payment instructions.
     *
     * @return string
     */
    protected function getPaymentInstructions()
    {
        return "Please complete your payment within 7 days to confirm your booking. Reference your invoice number {$this->reservation->invoice_number} in the payment description.";
    }

    /**
     * Get the bank details.
     *
     * @return array
     */
    protected function getBankDetails()
    {
        return [
            'bank_name' => 'ING Bank',
            'account_name' => 'Windkracht 13 B.V.',
            'iban' => 'NL56 INGB 0123 4567 89',
            'bic' => 'INGBNL2A',
            'reference' => $this->reservation->invoice_number
        ];
    }
}
