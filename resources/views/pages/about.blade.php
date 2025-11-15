@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="flex justify-center items-center py-16 min-h-[calc(100vh-8rem)]">
    <div class="w-1/2 bg-white rounded-3xl shadow-2xl p-12">
        <h2 class="text-4xl font-extrabold text-green-700 mb-6 text-center">About Us</h2>
        <p class="text-gray-500 mb-8 text-center">
            Mazraa.com is your trusted platform to explore, book, and manage farms and agricultural services efficiently.
            <br><br>
            Our mission is to simplify farm management and connect farmers, suppliers, and transport services in one seamless platform.
        </p>

        <h3 class="text-2xl font-semibold text-green-700 mb-4">Our Vision</h3>
        <p class="text-gray-600 mb-6">
            To become the leading digital marketplace for farms and agricultural services, providing convenience, transparency, and trust.
        </p>

        <h3 class="text-2xl font-semibold text-green-700 mb-4">Our Values</h3>
        <ul class="list-disc list-inside text-gray-600 space-y-2">
            <li>Customer satisfaction is our top priority.</li>
            <li>Innovation in agricultural solutions.</li>
            <li>Trust, transparency, and reliability.</li>
            <li>Empowering farmers and suppliers.</li>
        </ul>
    </div>
</div>
@endsection
