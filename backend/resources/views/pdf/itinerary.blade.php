<!DOCTYPE html>
<html lang="{{ $booking->language }}">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: middle;
        }
        .header-right { text-align: right; }
        .logo { color: #1a73e8; font-size: 20px; font-weight: bold; }
        .brand { font-size: 22px; font-weight: bold; color: #111; }

        /* ── META ROW ── */
        .meta-row {
            display: table;
            width: 100%;
            padding: 16px 20px;
        }
        .meta-left, .meta-right {
            display: table-cell;
            vertical-align: top;
        }
        .meta-right { text-align: right; }
        .emergency-label { font-size: 10px; color: #888; text-transform: uppercase; }
        .emergency-number { color: #e53e3e; font-size: 16px; font-weight: bold; }
        .emergency-note { font-size: 10px; color: #888; }
        .booking-ref-box {
            display: inline-block;
            background: #1a3a5c;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 18px;
            font-weight: bold;
        }
        .airline-codes { font-size: 10px; color: #888; margin-top: 4px; }

        /* ── PASSENGER BAR ── */
        .passenger-bar {
            background: #f5f5f5;
            padding: 12px 20px;
            margin: 0 20px;
            border-radius: 4px;
        }
        .passenger-label { font-size: 10px; color: #888; text-transform: uppercase; }
        .passenger-name { font-size: 16px; font-weight: bold; color: #111; }

        .warning { margin: 16px 20px; font-style: italic; color: #b7791f; font-size: 11px; }

        /* ── SEGMENT ── */
        .segment { border: 1px solid #ddd; margin: 16px 20px; border-radius: 6px; overflow: hidden; }

        .segment-header {
            display: table;
            width: 100%;
            padding: 10px 16px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 12px;
        }
        .segment-header-left, .segment-header-right {
            display: table-cell;
            vertical-align: middle;
        }
        .segment-header-right { text-align: right; }

        .segment-body { padding: 16px; }

        /* ── FLIGHT INFO ROW (airline + specs) ── */
        .flight-info-row {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .flight-info-left, .flight-info-right {
            display: table-cell;
            vertical-align: top;
        }
        .flight-info-right { text-align: right; }
        .airline-name { font-size: 15px; font-weight: bold; color: #1a3a5c; }
        .flight-sub { font-size: 11px; color: #555; margin-top: 2px; }
        .spec-label { font-size: 9px; color: #888; text-transform: uppercase; }
        .spec-value { font-size: 11px; font-weight: bold; }

        /* ── ROUTE ROW ── */
        .route-row {
            display: table;
            width: 100%;
            margin: 8px 0 16px;
        }
        .route-origin, .route-duration, .route-destination {
            display: table-cell;
            vertical-align: middle;
        }
        .route-duration { text-align: center; width: 120px; }
        .route-destination { text-align: right; }
        .airport-code { font-size: 32px; font-weight: bold; color: #1a3a5c; }
        .airport-name { font-size: 11px; color: #555; }
        .terminal { font-size: 10px; color: #888; text-transform: uppercase; }
        .time { font-size: 18px; font-weight: bold; }
        .date-small { font-size: 11px; color: #555; }
        .duration {
            font-size: 11px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }

        /* ── BAGGAGE ROW ── */
        .baggage-row {
            display: table;
            width: 100%;
            padding: 10px 16px;
            border-top: 1px solid #eee;
            background: #fafafa;
        }
        .baggage-left, .baggage-center, .baggage-right {
            display: table-cell;
            vertical-align: middle;
        }
        .baggage-right { text-align: right; }
        .baggage-label { font-size: 9px; color: #888; text-transform: uppercase; }
        .baggage-value { font-size: 12px; font-weight: bold; }
        .confirmed-badge {
            display: inline-block;
            border: 2px solid #38a169;
            color: #38a169;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        /* ── TICKET ROW ── */
        .ticket-row {
            display: table;
            width: 100%;
            padding: 10px 16px;
            background: #fff8f0;
            border-top: 1px solid #eee;
        }
        .ticket-cell {
            display: table-cell;
            vertical-align: top;
            padding-right: 40px;
        }
        .ticket-label { font-size: 9px; color: #888; text-transform: uppercase; }
        .ticket-value { font-size: 12px; font-weight: bold; color: #c05621; }

        .notes { margin: 20px; padding: 16px; background: #f9f9f9; border-radius: 4px; font-size: 11px; line-height: 1.8; }
        .footer { text-align: center; margin-top: 20px; padding: 12px; font-size: 10px; color: #aaa; border-top: 1px solid #eee; }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-left">
        <div class="logo">✈ everest travel</div>
    </div>
    <div class="header-right">
        <div class="brand">'wayers</div>
    </div>
</div>

{{-- META --}}
<div class="meta-row">
    <div class="meta-left">
        <div class="emergency-label">{{ $t['emergency_contact'] }}</div>
        <div class="emergency-number">+49 69 23 77 11</div>
        <div class="emergency-note">24/7 - {{ $t['only_emergencies'] }}</div>
    </div>
    <div class="meta-right">
        <div class="emergency-label">{{ $t['booking_reference'] }}</div>
        <div class="booking-ref-box">{{ $booking->booking_reference }}</div>
        <div class="airline-codes">{{ $t['airline_codes'] }}: {{ $booking->flightSegments->pluck('flight_number')->join(', ') }}</div>
    </div>
</div>

{{-- PASSENGER --}}
<div class="passenger-bar">
    <div class="passenger-label">{{ $t['passenger'] }}</div>
    <div class="passenger-name">{{ strtoupper($booking->passenger_name) }}</div>
</div>

{{-- WARNING --}}
<div class="warning">
    ⚠ {{ $t['warning'] }}
</div>

{{-- FLIGHT SEGMENTS --}}
@foreach ($booking->flightSegments as $segment)
<div class="segment">

    {{-- Segment Header --}}
    <div class="segment-header">
        <div class="segment-header-left">{{ $booking->booking_reference }}</div>
        <div class="segment-header-right">
            {{ \Carbon\Carbon::parse($segment->departure_date)->format('D, d M Y') }}
            @if($segment->departure_date !== $segment->arrival_date)
                → {{ \Carbon\Carbon::parse($segment->arrival_date)->format('D, d M Y') }}
            @endif
        </div>
    </div>

    {{-- Airline + Specs --}}
    <div class="segment-body">
        <div class="flight-info-row">
            <div class="flight-info-left">
                <div class="airline-name">{{ $segment->airline }}</div>
                <div class="flight-sub">{{ $t['flight'] }} {{ $segment->flight_number }}</div>
                <div class="flight-sub">{{ $t['operated_by'] }} {{ strtoupper($segment->operating_airline) }}</div>
            </div>
            <div class="flight-info-right">
                <div class="spec-label">{{ $t['aircraft'] }}</div>
                <div class="spec-value">{{ $segment->aircraft }}</div>
                <div style="margin-top:6px;">
                    <div class="spec-label">{{ $t['cabin'] }}</div>
                    <div class="spec-value">{{ $segment->cabin }}</div>
                </div>
                @if(!empty($segment->distance))
                <div style="margin-top:6px;">
                    <div class="spec-label">{{ $t['distance'] ?? 'DISTANCE' }}</div>
                    <div class="spec-value">{{ $segment->distance }}</div>
                </div>
                @endif
                @if(!empty($segment->co2))
                <div style="margin-top:6px;">
                    <div class="spec-label">CO&#x2082; {{ $t['estimate'] ?? 'ESTIMATE' }}</div>
                    <div class="spec-value">{{ $segment->co2 }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Route --}}
        <div class="route-row">
            <div class="route-origin">
                <div class="airport-code">{{ $segment->from }}</div>
                <div class="airport-name">{{ $segment->from_city ?? '' }}</div>
                <div class="time">{{ $segment->departure_time }}</div>
                <div class="date-small">{{ \Carbon\Carbon::parse($segment->departure_date)->format('D, d M') }}</div>
                @if($segment->terminal)
                    <div class="terminal">{{ $t['terminal'] }} {{ $segment->terminal }}</div>
                @endif
            </div>
            <div class="route-duration">
                <div class="duration">
                    ✈<br>
                    @php
                        $dep = \Carbon\Carbon::parse($segment->departure_date . ' ' . $segment->departure_time);
                        $arr = \Carbon\Carbon::parse($segment->arrival_date . ' ' . $segment->arrival_time);
                        $diff = $dep->diff($arr);
                    @endphp
                    {{ $diff->h + ($diff->days * 24) }}h {{ $diff->i }}min
                </div>
            </div>
            <div class="route-destination">
                <div class="airport-code">{{ $segment->to }}</div>
                <div class="airport-name">{{ $segment->to_city ?? '' }}</div>
                <div class="time">{{ $segment->arrival_time }}</div>
                <div class="date-small">{{ \Carbon\Carbon::parse($segment->arrival_date)->format('D, d M') }}</div>
                @if($segment->arrival_terminal)
                    <div class="terminal">{{ $t['terminal'] }} {{ $segment->arrival_terminal }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Baggage --}}
    <div class="baggage-row">
        <div class="baggage-left">
            <div class="baggage-label">{{ $t['checked_baggage'] }}</div>
            <div class="baggage-value">{{ $segment->baggage }}</div>
        </div>
        <div class="baggage-center">
            <div class="baggage-label">{{ $t['carry_on'] }}</div>
            <div class="baggage-value">{{ $segment->carry_on }}</div>
        </div>
        <div class="baggage-right">
            <div class="confirmed-badge">{{ $t['confirmed'] }}</div>
        </div>
    </div>

    {{-- Ticket --}}
    <div class="ticket-row">
        <div class="ticket-cell">
            <div class="ticket-label">{{ $t['passenger'] }}</div>
            <div class="ticket-value">{{ strtoupper($booking->passenger_name) }}</div>
        </div>
        <div class="ticket-cell">
            <div class="ticket-label">{{ $t['e_ticket'] }}</div>
            <div class="ticket-value">{{ $segment->ticket_number }}</div>
        </div>
    </div>

</div>
@endforeach

{{-- NOTES --}}
<div class="notes">
    <strong>{{ $t['notes_title'] }}</strong><br><br>
    {{ $t['note_1'] }}<br>
    {{ $t['note_2'] }}<br>
    {{ $t['note_3'] }}<br>
    {{ $t['note_4'] }}
</div>

{{-- FOOTER --}}
<div class="footer">
    {{ $t['footer'] }}
</div>

</body>
</html>




