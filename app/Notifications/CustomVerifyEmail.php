<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Get the verification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Welcome to Windkracht 13 - Verify Your Email')
            ->greeting('Hello!')
            ->line('Welcome to Windkracht 13 Kitesurf School! We\'re excited to have you join our community of kitesurfing enthusiasts.')
            ->line('Please click the button below to verify your email address and set up your account password.')
            ->action('Verify Email Address', $url)
            ->line('After verification, you\'ll be able to book kitesurfing lessons, track your progress, and more.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Catch the wind with us,<br>The Windkracht 13 Team');
    }
    
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
