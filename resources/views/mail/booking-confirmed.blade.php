@extends('mail.layout', ['title' => 'Booking Confirmed'])

@section('content')
    <h2>Hello {{ $notifiable->name }},</h2>
    <p>Your booking for <strong style="color: #1d5c42;">{{ $booking->farm->name }}</strong> has been successfully confirmed. We are thrilled to host you!</p>

    <div class="details-box">
        <div class="details-row">
            <span class="details-label">Booking ID</span>
            <span class="details-value">#MZ-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Check-in</span>
            <span class="details-value">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y h:i A') }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Check-out</span>
            <span class="details-value">{{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y h:i A') }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Total Paid</span>
            <span class="details-value" style="color: #1d5c42;">{{ number_format($booking->total_price, 2) }} JOD</span>
        </div>
    </div>

    <center>
        <a href="{{ url('/my-bookings-list') }}" class="button">View My Bookings</a>
    </center>

    <p style="margin-top: 30px;">Thank you for choosing Mazraa.com!</p>
@endsection
