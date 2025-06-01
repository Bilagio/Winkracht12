@component('mail::message')
# Important Notice About Your Kitesurfing Lesson

Dear {{ $user->name }},

{{ $message }}

**Your booking details:**

**Reservation ID:** KITE-{{ $reservation->id }}  
**Date:** {{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}  
**Time:** {{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}  

@component('mail::panel')
## Next Steps

We have a few options for you:

1. **Reschedule your lesson** - Click the button below to select a new date and time
2. **Get assigned to another instructor** - We can try to find another available instructor for your original time slot
3. **Request a refund** - If none of the above options work for you

Please let us know your preference by replying to this email or by calling us at +31 123 456 789.
@endcomponent

@component('mail::button', ['url' => $reschedulingLink, 'color' => 'blue'])
Reschedule Your Lesson
@endcomponent

We sincerely apologize for any inconvenience this may cause and appreciate your understanding.

Thanks,<br>
The Windkracht 13 Team
@endcomponent
