@extends('mail.layout', ['title' => 'New Transport Assigned'])

@section('content')
    <h2>Hello {{ $notifiable->name }},</h2>
    <p>You have been assigned to a new trip. Please review the details below and ensure you are ready on time.</p>

    <div class="details-box">
        <div class="details-row">
            <span class="details-label">Trip ID</span>
            <span class="details-value">#TRP-{{ str_pad($transport->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Pickup Location</span>
            <span class="details-value">{{ $transport->start_and_return_point }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Destination Farm</span>
            <span class="details-value">{{ $transport->farm->name ?? 'N/A' }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Arrival Time</span>
            <span class="details-value">{{ \Carbon\Carbon::parse($transport->Farm_Arrival_Time)->format('M d, Y h:i A') }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Passengers</span>
            <span class="details-value">{{ $transport->passengers }}</span>
        </div>
    </div>

    <center>
        <a href="{{ route('transport.driver.dashboard') }}" class="button info">View Trip Details</a>
    </center>

    <p style="margin-top: 30px;">Drive safely!</p>
@endsection
