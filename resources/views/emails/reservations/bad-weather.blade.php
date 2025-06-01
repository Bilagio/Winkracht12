@component('mail::message')
# Weather Alert for Your Kitesurfing Lesson

Dear {{ $user->name }},

We are writing to inform you about important weather conditions that will affect your upcoming kitesurfing lesson.

**Current Weather Forecast:**
- **Wind Force:** {{ $windForce }} Beaufort
- **Wind Speed:** {{ $weatherDetails['wind_speed'] ?? '55+ km/h' }}
- **Wind Direction:** {{ $weatherDetails['wind_direction'] ?? 'Variable' }}
- **Wave Height:** {{ $weatherDetails['wave_height'] ?? '2+ meters' }}

@component('mail::panel')
## Safety Notice

Due to the forecasted wind force exceeding our safety threshold of 10 Beaufort, we regret to inform you that your lesson scheduled for **{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}** at **{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}** must be rescheduled.

At Windkracht 13, your safety is our top priority. These weather conditions are not suitable for beginners and can pose risks even for experienced kitesurfers.
@endcomponent

@component('mail::button', ['url' => $reschedulingLink, 'color' => 'blue'])
Reschedule Your Lesson
@endcomponent

If you have any questions or need assistance with rescheduling, please don't hesitate to contact us at info@windkracht13.nl or call us at +31 123 456 789.

Thanks for your understanding,<br>
The Windkracht 13 Team
@endcomponent
