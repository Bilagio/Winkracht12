<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmed extends Notification
{
    use Queueable;

    /**
     * The reservation instance.
     *
     * @var \App\Models\Reservation
     */
    protected $reservation;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Reservation $reservation
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
        $lessonName = $this->reservation->lessonPackage ? $this->reservation->lessonPackage->name : 'Kitesurfing Lesson';
        $locationName = $this->reservation->location ? $this->reservation->location->name : 'Our location';
        
        return (new MailMessage)
            ->subject('Kitesurfing Lesson Reservation Confirmation')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for booking a kitesurfing lesson with us.')
            ->line('Your reservation details:')
            ->line('- Lesson: ' . $lessonName)
            ->line('- Location: ' . $locationName)
            ->line('- Date: ' . $this->reservation->date->format('l, F j, Y'))
            ->line('- Time: ' . $this->reservation->time)
            ->line('- Participants: ' . $this->reservation->participants)
            ->line('- Status: ' . ucfirst($this->reservation->status))
            ->line('- Total Price: €' . number_format($this->reservation->total_price, 2))
            ->action('View Your Booking', url('/customer/reservations'))
            ->line('If you have any questions, please don\'t hesitate to contact us.')
            ->line('Thank you for choosing Windkracht 13 Kitesurf School!');
    }
}
