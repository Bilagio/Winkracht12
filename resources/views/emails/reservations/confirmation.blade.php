@component('mail::message')
# Reservation Confirmation

Dear {{ $reservation->user->name }},

Thank you for booking a kitesurfing lesson with Windkracht 13. Below are the details of your reservation:

**Invoice Number:** {{ $reservation->invoice_number }}  
**Status:** {{ ucfirst($reservation->status) }}

## Lesson Details

**Package:** {{ $reservation->lessonPackage->name }}  
**Date:** {{ $reservation->date->format('l, F j, Y') }}  
**Time:** {{ $reservation->time }}  
**Duration:** {{ $reservation->lessonPackage->duration }} minutes  
**Location:** {{ $reservation->location->name }}  
**Number of Participants:** {{ $reservation->participants }}

## Payment Details

**Total Amount Due:** €{{ number_format($reservation->total_price, 2) }}

Please make your payment to the following bank account:

**Bank:** Windkracht 13 Kitesurfing  
**IBAN:** NL12 ABCD 0123 4567 89  
**Reference:** {{ $reservation->invoice_number }}

Your reservation will be confirmed once we receive your payment. Payment must be received within 5 days or your reservation may be cancelled.

@if(is_array($reservation->additional_participants) && count($reservation->additional_participants) > 0)
## Additional Participants

@foreach($reservation->additional_participants as $index => $participant)
**Participant {{ $index + 1 }}:** {{ $participant['name'] }}
@endforeach
@endif

@component('mail::button', ['url' => route('customer.reservations.show', $reservation)])
View Reservation Details
@endcomponent

If you have any questions, please don't hesitate to contact us.

Thank you,<br>
Windkracht 13 Kitesurfing School
@endcomponent
