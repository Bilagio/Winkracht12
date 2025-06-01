<?php

namespace App\Notifications;

use App\Models\Cancellation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CancellationRequestNotification extends Notification
{
    use Queueable;

    protected $cancellation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Cancellation $cancellation)
    {
        $this->cancellation = $cancellation;
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
        $reservation = $this->cancellation->reservation;
        $user = $reservation->user;
        
        return (new MailMessage)
            ->subject('New Cancellation Request - ' . $user->name)
            ->line('A customer has requested to cancel their kitesurfing lesson.')
            ->line('Customer: ' . $user->name)
            ->line('Date: ' . $reservation->date->format('d M, Y') . ' at ' . $reservation->time)
            ->line('Package: ' . $reservation->lessonPackage->name)
            ->line('Reason for cancellation: ' . $this->cancellation->reason)
            ->action('Review Request', url(route('admin.cancellations.show', $this->cancellation)))
            ->line('Please review this request as soon as possible.');
    }
}
