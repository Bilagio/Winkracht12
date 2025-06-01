@component('mail::message')
# Your Kitesurfing Lesson is Confirmed!

Dear {{ $reservation->user->name }},

Great news! Your payment has been received and your kitesurfing lesson is now **confirmed**. 

## Lesson Details:
- **Date:** {{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}
- **Location:** {{ $reservation->location->name }}
- **Package:** {{ $reservation->lessonPackage->name }}
- **Duration:** {{ $reservation->lessonPackage->duration }} minutes
- **Participants:** {{ $reservation->participants }}

@if($reservation->instructor)
**Your instructor will be:** {{ $reservation->instructor->name }}
@endif

## What to Bring:
- Swimwear
- Towel
- Sunscreen
- Change of clothes
- Water bottle

Please arrive 15 minutes before your scheduled time to complete any necessary paperwork and prepare for your lesson.

If you need to make any changes to your reservation, please contact us as soon as possible.

@component('mail::button', ['url' => route('customer.reservations.show', $reservation)])
View Reservation
@endcomponent

We look forward to seeing you on the water!

Thanks,<br>
The Windkracht 13 Kitesurf School Team
@endcomponent
