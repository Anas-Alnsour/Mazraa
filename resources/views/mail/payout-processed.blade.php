@extends('mail.layout', ['title' => 'Payout Processed'])

@section('content')
    <h2>Hello {{ $notifiable->name }},</h2>
    <p>We are writing to confirm that a payout request has been successfully processed by our financial team.</p>

    <div class="details-box">
        <div class="details-row">
            <span class="details-label">Transaction ID</span>
            <span class="details-value">#FIN-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Processed Date</span>
            <span class="details-value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</span>
        </div>
        <div class="details-row">
            <span class="details-label">Amount Transferred</span>
            <span class="details-value" style="color: #1d5c42;">{{ number_format($transaction->amount, 2) }} JOD</span>
        </div>
    </div>

    <p>The funds should reflect in your bank account shortly, depending on your bank's standard processing times.</p>

    <center>
        <a href="{{ url('/portal/login') }}" class="button info">Login to Portal</a>
    </center>

    <p style="margin-top: 30px;">Thank you for partnering with Mazraa.com!</p>
@endsection
