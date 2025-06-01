<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation Confirmation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #4a5568;
            padding: 20px;
            margin: 0;
            background-color: #f8f8f8;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }
        .highlight {
            color: #e67e22;
        }
        h1 {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 16px;
            color: #2d3748;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .item {
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            color: #4a5568;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #2d3748;
        }
        .price {
            font-size: 18px;
            font-weight: bold;
            color: #3498db;
        }
        .payment-info {
            background-color: #f3f9ff;
            padding: 20px;
            border-radius: 5px;
            margin: 25px 0;
        }
        .important-text {
            font-weight: bold;
            color: #e67e22;
        }
        .bank-details {
            background-color: #f7fafc;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #718096;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
        }
        .invoice {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        .invoice th, .invoice td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: left;
        }
        .invoice th {
            background-color: #f7fafc;
            font-weight: bold;
        }
        .invoice tr.total {
            background-color: #f3f9ff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Windkracht <span class="highlight">13</span></div>
            <p>Invoice #{{ $reservation->invoice_number }}</p>
        </div>

        <h1>Reservation Confirmation</h1>
        
        <p>Dear {{ $user->name }},</p>
        
        <p>Thank you for booking a kitesurfing lesson with Windkracht 13! We're excited to have you join us.</p>
        
        <div class="payment-info">
            <p><span class="important-text">Important:</span> {{ $paymentInstructions }}</p>
        </div>
        
        <h2>Invoice Details</h2>
        
        <table class="invoice">
            <tr>
                <th>Item</th>
                <th>Details</th>
                <th>Price</th>
            </tr>
            <tr>
                <td>{{ $lessonPackage->name }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }} at {{ $reservation->time }}<br>
                    Location: {{ $location->name }}<br>
                    Participants: {{ $reservation->participants }}
                </td>
                <td>€{{ number_format($lessonPackage->price, 2) }} per person</td>
            </tr>
            <tr class="total">
                <td colspan="2">Total</td>
                <td>€{{ number_format($reservation->total_price, 2) }}</td>
            </tr>
        </table>
        
        <h2>Customer Details</h2>
        
        <div class="item">
            <span class="label">Name:</span>
            <span class="value">{{ $user->name }}</span>
        </div>
        
        <div class="item">
            <span class="label">Email:</span>
            <span class="value">{{ $user->email }}</span>
        </div>
        
        @if($user->mobile)
        <div class="item">
            <span class="label">Phone:</span>
            <span class="value">{{ $user->mobile }}</span>
        </div>
        @endif
        
        <h2>Reservation Details</h2>
        
        <div class="item">
            <span class="label">Invoice Number:</span>
            <span class="value">{{ $reservation->invoice_number }}</span>
        </div>
        
        <div class="item">
            <span class="label">Package:</span>
            <span class="value">{{ $lessonPackage->name }}</span>
        </div>
        
        <div class="item">
            <span class="label">Date & Time:</span>
            <span class="value">{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }} at {{ $reservation->time }}</span>
        </div>
        
        <div class="item">
            <span class="label">Location:</span>
            <span class="value">{{ $location->name }}, {{ $location->city }}</span>
        </div>
        
        <div class="item">
            <span class="label">Number of Participants:</span>
            <span class="value">{{ $reservation->participants }}</span>
        </div>
        
        @if($reservation->notes)
        <div class="item">
            <span class="label">Special Requests:</span>
            <span class="value">{{ $reservation->notes }}</span>
        </div>
        @endif
        
        @if(!empty($reservation->additional_participants))
        <h2>Additional Participants</h2>
        @foreach($reservation->additional_participants as $participant)
        <div class="item">
            <span class="label">Participant:</span>
            <span class="value">{{ $participant['name'] ?? 'Name not provided' }}</span>
        </div>
        @if(!empty($participant['email']))
        <div class="item">
            <span class="label">Email:</span>
            <span class="value">{{ $participant['email'] }}</span>
        </div>
        @endif
        @if(!empty($participant['phone']))
        <div class="item">
            <span class="label">Phone:</span>
            <span class="value">{{ $participant['phone'] }}</span>
        </div>
        @endif
        <br>
        @endforeach
        @endif
        
        <div class="item">
            <span class="label">Total Price:</span>
            <span class="price">€{{ number_format($reservation->total_price, 2) }}</span>
        </div>
        
        <h2>Payment Details</h2>
        
        <p>To confirm your reservation, please complete your payment using the following bank details:</p>
        
        <div class="bank-details">
            <div class="item">
                <span class="label">Bank:</span>
                <span class="value">{{ $bankDetails['bank_name'] }}</span>
            </div>
            <div class="item">
                <span class="label">Account Name:</span>
                <span class="value">{{ $bankDetails['account_name'] }}</span>
            </div>
            <div class="item">
                <span class="label">IBAN:</span>
                <span class="value">{{ $bankDetails['iban'] }}</span>
            </div>
            <div class="item">
                <span class="label">BIC:</span>
                <span class="value">{{ $bankDetails['bic'] }}</span>
            </div>
            <div class="item">
                <span class="label">Amount:</span>
                <span class="value">€{{ number_format($reservation->total_price, 2) }}</span>
            </div>
            <div class="item">
                <span class="label">Reference:</span>
                <span class="value"><strong>{{ $bankDetails['reference'] }}</strong></span>
            </div>
        </div>
        
        <p>If you have any questions or need to make changes to your reservation, please don't hesitate to contact us at info@windkracht13.nl or call +31 123 456 789.</p>
        
        <p style="text-align: center;">
            <a href="{{ route('customer.reservations.show', $reservation) }}" class="button">View Your Reservation</a>
        </p>
        
        <div class="footer">
            <p>Windkracht 13 | Beach Boulevard 13, 1234 AB Zandvoort, The Netherlands</p>
            <p>&copy; {{ date('Y') }} Windkracht 13. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
