<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BadWeatherAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;
    protected $windForce;
    protected $weatherDetails;

    /**
     * Create a new notification instance.
     *
     * @param  Reservation  $reservation
     * @param  int  $windForce
     * @param  array  $weatherDetails
     * @return void
     */
    public function __construct(Reservation $reservation, $windForce, array $weatherDetails = [])
    {
        $this->reservation = $reservation;
        $this->windForce = $windForce;
        $this->weatherDetails = $weatherDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Weather Alert: Changes to Your Kitesurfing Lesson')
            ->markdown('emails.reservations.bad-weather', [
                'reservation' => $this->reservation,
                'user' => $notifiable,
                'windForce' => $this->windForce,
                'weatherDetails' => $this->weatherDetails,
                'reschedulingLink' => route('customer.reservations.edit', $this->reservation)
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'wind_force' => $this->windForce,
            'message' => 'Your lesson may be affected by bad weather conditions.'
        ];
    }
}
