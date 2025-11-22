@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="relative bg-gradient-to-r from-green-100 via-green-200 to-green-100 h-screen flex justify-center items-center py-16 min-h-[calc(100vh-8rem)]">
    <div class="w-1/2 bg-white rounded-3xl shadow-2xl p-12">
        <h2 class="text-4xl font-extrabold text-green-700 mb-6 text-center">About Us</h2>
        <p class="text-gray-500 mb-8 text-center">
                 Mazraa.com is a modern digital platform designed to simplify farm booking 
                 in one seamless experience. Whether you're planning a family
                getaway, hosting a celebration, or need farm supplies delivered before or during
                your stay, Mazraa.com has everything you need in one place.
        </p>

        <h3 class="text-2xl font-semibold text-green-700 mb-4">Our Vision</h3>
        <p class="text-gray-600 mb-6">
                                To become the leading digital marketplace for farms ,
                    providing convenience, transparency, and trust for users, farm owners, and
                    service providers.
        </p>
                <h2 class="text-2xl font-semibold text-green-700 mb-4">
                    What We Offer
                </h2>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    <li>Explore and book farms with detailed information and photos.</li>
                    <li>Guest access without registration, plus full accounts for registered users.</li>
                    <li>Order supplies to the farm before arrival or during your stay.</li>
                    <li>Book transportation to and from the farm through the website.</li>
                    <li>Secure online payments and a favorites list for your best farms.</li>
                </ul>

        <h3 class="text-2xl font-semibold text-green-700 mb-4">Our Values</h3>
        <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li>Customer satisfaction is our top priority.</li>
                    
                    <li>Trust, transparency, and reliability.</li>
                    
        </ul>
    </div>
</div>
@endsection
