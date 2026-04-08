@extends('mail.layout', ['title' => 'New Supply Assigned'])

@section('content')
    <h2>Hello {{ $notifiable->name }},</h2>
    <p>You have been assigned to a new supply delivery order. Please review the details in your dashboard.</p>

    <div class="details-box">
        <div class="details-row">
            <span class="details-label">Invoice ID</span>
            <span class="details-value">#INV-{{ str_pad($invoiceId, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Status</span>
            <span class="details-value">Waiting for Pickup</span>
        </div>
    </div>

    <center>
        <a href="{{ url('/supply-companies/dashboard') }}" class="button info">View Dispatch Board</a>
    </center>

    <p style="margin-top: 30px;">Drive safely!</p>
@endsection
