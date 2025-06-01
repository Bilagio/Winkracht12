<?php

namespace App\Notifications;

use App\Models\Cancellation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CancellationReviewedNotification extends Notification
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
        $messageBuilder = (new MailMessage)
            ->subject('Your Cancellation Request - ' . ($this->cancellation->status === 'approved' ? 'Approved' : 'Rejected'))
            ->line('We have reviewed your request to cancel your kitesurfing lesson.')
            ->line('Date: ' . $reservation->date->format('d M, Y') . ' at ' . $reservation->time)
            ->line('Package: ' . $reservation->lessonPackage->name);

        if ($this->cancellation->status === 'approved') {
            $messageBuilder
                ->line('Your cancellation request has been approved.')
                ->line('You can now reschedule your lesson at a new date and time.')
                ->action('Reschedule Your Lesson', url(route('customer.reservations.reschedule', $reservation)));
        } else {
            $messageBuilder
                ->line('We regret to inform you that your cancellation request has been rejected.')
                ->line('Your original booking remains valid.');
        }

        if ($this->cancellation->admin_comment) {
            $messageBuilder->line('Comment from our team: ' . $this->cancellation->admin_comment);
        }

        $messageBuilder->line('If you have any questions, please contact our support team.');

        return $messageBuilder;
    }
}
