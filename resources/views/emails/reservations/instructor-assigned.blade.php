@component('mail::message')
# New Lesson Assignment Confirmed

Hello {{ $reservation->instructor->name }},

A kitesurfing lesson has been confirmed and assigned to you.

## Lesson Details:
- **Date:** {{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}
- **Location:** {{ $reservation->location->name }}
- **Package:** {{ $reservation->lessonPackage->name }}
- **Duration:** {{ $reservation->lessonPackage->duration }} minutes
- **Participants:** {{ $reservation->participants }}

### Student Information:
- **Name:** {{ $reservation->user->name }}
- **Email:** {{ $reservation->user->email }}
@if($reservation->user->mobile)
- **Phone:** {{ $reservation->user->mobile }}
@endif

@if($reservation->notes)
### Special Notes:
{{ $reservation->notes }}
@endif

Please ensure you're prepared and at the location at least 15 minutes before the scheduled time.

@component('mail::button', ['url' => route('instructor.schedule')])
View Your Schedule
@endcomponent

If you have any questions or are unable to conduct this lesson, please contact the admin immediately.

Thanks,<br>
The Windkracht 13 Kitesurf School Admin Team
@endcomponent
