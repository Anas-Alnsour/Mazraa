@extends('mail.layout', ['title' => 'Farm Approved'])

@section('content')
    <h2>Hello {{ $notifiable->name }},</h2>
    <p>We have incredible news! Your farm listing <strong>{{ $farm->name }}</strong> has been reviewed and <strong style="color: #1d5c42;">approved</strong> by our moderation team.</p>

    <p>Your property is now live on the Mazraa.com platform and is fully available for customers to book.</p>

    <center>
        <a href="{{ url('/owner/dashboard') }}" class="button">Go to Dashboard</a>
    </center>

    <p style="margin-top: 30px;">We are excited to see you thrive on our platform!</p>
@endsection
