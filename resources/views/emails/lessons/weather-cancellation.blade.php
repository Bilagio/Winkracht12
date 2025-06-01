@component('mail::message')
# Your Kitesurfing Lesson Has Been Cancelled Due to Weather Conditions

Dear {{ $reservation->user->name }},

We regret to inform you that your kitesurfing lesson scheduled for **{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}** at **{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}** has been cancelled due to unsafe weather conditions.

## Weather Information:
- **Current Wind Force:** {{ $windForce }} Beaufort
- **Safety Threshold:** 10 Beaufort

When wind forces exceed 10 Beaufort, conditions become unsafe for kitesurfing lessons, particularly for instruction purposes. Your safety is our top priority.

## Lesson Details:
- **Date:** {{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}
- **Location:** {{ $reservation->location->name }}
- **Package:** {{ $reservation->lessonPackage->name }}

@if($additionalNotes)
## Additional Information:
{{ $additionalNotes }}
@endif

We sincerely apologize for any inconvenience this may cause. Please contact us to reschedule your lesson when weather conditions improve.

@component('mail::button', ['url' => route('welcome')])
Visit Our Website
@endcomponent

Thank you for your understanding.

Best regards,<br>
The Windkracht 13 Kitesurf School Team
@endcomponent
