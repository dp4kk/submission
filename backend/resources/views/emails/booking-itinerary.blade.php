<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Flight Itinerary</title>
</head>
<body>
    <h2>Hello {{ $booking->passenger_name }},</h2>

    <p>
        Your booking has been confirmed.
    </p>

    <p>
        Booking Reference:
        <strong>{{ $booking->booking_reference }}</strong>
    </p>

    <p>
        Your itinerary PDF is attached with this email.
    </p>

    <p>
        Thank you.
    </p>
</body>
</html>