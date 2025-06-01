@component('mail::message')
# Your Kitesurfing Lesson is Coming Up!

Hello {{ $user->name }},

This is a friendly reminder that your kitesurfing lesson is coming up soon!

**Lesson Details:**
- **Date:** {{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}
- **Location:** {{ $location->name }}, {{ $location->city }}
- **Lesson Package:** {{ $lessonPackage->name }}
- **Duration:** {{ $lessonPackage->duration }} minutes

@component('mail::panel')
## Weather Forecast
We're keeping an eye on the weather conditions for your lesson:

- **Temperature:** {{ $weatherForecast['temperature'] }}
- **Wind Speed:** {{ $weatherForecast['windSpeed'] }}
- **Wind Direction:** {{ $weatherForecast['windDirection'] }}
- **Conditions:** {{ $weatherForecast['conditions'] }}
@endcomponent

## What to Bring
- Swimwear
- Towel
- Sunscreen
- Change of clothes
- Water bottle
- Positive energy!

@component('mail::button', ['url' => route('customer.reservations.show', $reservation), 'color' => 'blue'])
View Booking Details
@endcomponent

If you need to make any changes to your reservation, please contact us at least 48 hours before your scheduled lesson.

We look forward to seeing you on the water!

Thanks,<br>
The Windkracht 13 Team

@component('mail::subcopy')
If you no longer wish to receive these reminders, you can update your notification preferences in your [account settings]({{ route('profile.edit') }}).
@endcomponent
@endcomponent
