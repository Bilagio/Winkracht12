<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorIllness extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;
    protected $instructor;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param  Reservation  $reservation
     * @param  User  $instructor
     * @param  string|null  $message
     * @return void
     */
    public function __construct(Reservation $reservation, User $instructor, $message = null)
    {
        $this->reservation = $reservation;
        $this->instructor = $instructor;
        $this->message = $message;
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
            ->subject('Important: Change to Your Kitesurfing Lesson - Instructor Unavailable')
            ->markdown('emails.reservations.instructor-illness', [
                'reservation' => $this->reservation,
                'user' => $notifiable,
                'instructor' => $this->instructor,
                'message' => $this->message ?? 'Unfortunately, your instructor has fallen ill and will not be available for your scheduled lesson.',
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
            'instructor_id' => $this->instructor->id,
            'message' => 'Your instructor is unavailable due to illness.'
        ];
    }
}
