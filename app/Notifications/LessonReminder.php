<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LessonReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     *
     * @param  Reservation  $reservation
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
        // Check if the user has enabled email reminders
        $channels = ['database'];
        if ($notifiable->notification_preferences['email_reminders'] ?? false) {
            $channels[] = 'mail';
        }
        return $channels;
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
            ->subject('Reminder: Your Kitesurfing Lesson is Coming Up!')
            ->markdown('emails.reservations.reminder', [
                'reservation' => $this->reservation,
                'user' => $notifiable,
                'lessonPackage' => $this->reservation->lessonPackage,
                'location' => $this->reservation->location,
                'weatherForecast' => $this->getWeatherForecast()
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
            'date' => $this->reservation->date,
            'time' => $this->reservation->time,
            'message' => 'Reminder: Your kitesurfing lesson is scheduled soon.'
        ];
    }

    /**
     * Get the weather forecast for the lesson.
     * This is a placeholder and would be replaced by a real weather API call.
     *
     * @return array
     */
    private function getWeatherForecast()
    {
        // In a real implementation, this would call a weather API
        return [
            'temperature' => '22°C',
            'windSpeed' => '18 km/h',
            'windDirection' => 'NW',
            'conditions' => 'Partly cloudy',
        ];
    }
}
