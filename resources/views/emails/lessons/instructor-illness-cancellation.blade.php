@component('mail::message')
# Your Kitesurfing Lesson Has Been Cancelled

Dear {{ $reservation->user->name }},

We regret to inform you that your kitesurfing lesson scheduled for **{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}** at **{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}** has been cancelled due to instructor illness.

## Lesson Details:
- **Date:** {{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}
- **Location:** {{ $reservation->location->name }}
- **Package:** {{ $reservation->lessonPackage->name }}

@if($additionalNotes)
## Additional Information:
{{ $additionalNotes }}
@endif

We sincerely apologize for any inconvenience this may cause. Please contact us to reschedule your lesson at your earliest convenience.

@component('mail::button', ['url' => route('welcome')])
Visit Our Website
@endcomponent

Thank you for your understanding.

Best regards,<br>
The Windkracht 13 Kitesurf School Team
@endcomponent
