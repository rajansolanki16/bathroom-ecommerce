@php
    use Carbon\Carbon;
    $booking = App\Models\Booking::find($mailData); 
    $transaction = App\Models\Transaction::where('transaction_id', $booking->transaction_id)->first();
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            color: #333;
        }
        .details {
            margin: 20px 0;
        }
        .details p {
            margin: 5px 0;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Booking Status</h2>
        <p>Dear Customer,</p>
        <p>Thank you for your booking. Here are your details:</p>
        <div class="details">
            <p><strong>Booking ID     :</strong> {{ $booking->id }}</p>
            <p><strong>Check-in       :</strong> {{ Carbon::parse($booking->check_in)->format('d-m-Y') }}</p>
            <p><strong>Check-out      :</strong> {{ Carbon::parse($booking->check_out)->format('d-m-Y') }}</p>
            <p><strong>Adults         :</strong> {{ $booking->adults }}</p> 
            <p><strong>Children       :</strong> {{ $booking->children }}</p>
            <p><strong>Number of Rooms:</strong> {{ $booking->room_count }}</p>
            <p><strong>Total Amount   :</strong> {{ $transaction->amount }}</p>
            <p><strong>Transaction ID :</strong> {{ $transaction->transaction_id }}</p>
            <p><strong>Payment Mode   :</strong> {{ $transaction->method }}</p>
            <p><strong>Payment Status :</strong> 
                @switch($transaction->status)
                    @case(0)
                        FAILED
                        @break
                    @case(1)
                        PAID
                        @break
                    @case(2)
                        @if ($transaction->method == 'CASH')
                            PENDING
                        @else
                            PROCESSING
                        @endif
                        @break
                    @default
                        UNKNOWN
                @endswitch
            </p>
        </div>
        <p>feel free to contact us on: help@knightoasis.in</p>
    </div>
</body>
</html>
