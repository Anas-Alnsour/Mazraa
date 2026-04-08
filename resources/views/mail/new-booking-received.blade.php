@extends('mail.layout', ['title' => 'New Booking Received'])

@section('content')
    <h2>Hello {{ $notifiable->name }},</h2>
    <p>Great news! You have received a new paid booking for your farm <strong style="color: #1d5c42;">{{ $booking->farm->name }}</strong>.</p>

    <div class="details-box">
        <div class="details-row">
            <span class="details-label">Booking ID</span>
            <span class="details-value">#MZ-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Guest Name</span>
            <span class="details-value">{{ $booking->user->name ?? 'Guest' }}</span>
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
            <span class="details-label">Net Payout</span>
            <span class="details-value" style="color: #1d5c42;">{{ number_format($booking->net_owner_amount, 2) }} JOD</span>
        </div>
    </div>

    <center>
        <a href="{{ url('/owner/dashboard') }}" class="button">View Dashboard</a>
    </center>

    <p style="margin-top: 30px;">Thank you for partnering with Mazraa.com!</p>
@endsection
