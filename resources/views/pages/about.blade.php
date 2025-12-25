{{-- resources/views/about.blade.php --}}
@extends('layouts.app')
@section('title', 'About Us')

@section('content')
    <div class="relative w-full min-h-screen flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 overflow-hidden bg-gray-900">

        <div class="absolute inset-0 z-0">
            <img src="{{ asset('backgrounds/Contact&about.JPG') }}" alt="Background" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-green-900/80"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto space-y-12">

            <div class="text-center space-y-4">
                <h1 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-white drop-shadow-lg">
                    About Mazraa.com
                </h1>
                <p class="text-lg md:text-xl text-gray-200 max-w-3xl mx-auto leading-relaxed">
                    A modern digital platform designed to simplify farm booking in one seamless experience. We bridge the gap between nature lovers and unique farm stays.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 hover:bg-white/20 transition duration-300 shadow-xl group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-green-500/20 rounded-full group-hover:bg-green-500/40 transition">
                            <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold text-white">Our Vision</h3>
                    </div>
                    <p class="text-gray-200 leading-relaxed">
                        To become the leading digital marketplace for farms, providing convenience, transparency, and trust for everyone seeking an escape to nature.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 hover:bg-white/20 transition duration-300 shadow-xl group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-yellow-500/20 rounded-full group-hover:bg-yellow-500/40 transition">
                            <svg class="w-8 h-8 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold text-white">Our Values</h3>
                    </div>
                    <ul class="space-y-3 text-gray-200">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Customer satisfaction is our top priority.
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Trust, transparency, and reliability.
                        </li>
                    </ul>
                </div>
            </div>

            <div>
                <h3 class="text-2xl font-bold text-white text-center mb-8 uppercase tracking-widest opacity-80">What We Offer</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:-translate-y-2 transition duration-300">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Explore & Book</h4>
                        <p class="text-gray-600 text-sm">Browse farms with detailed photos and book instantly.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:-translate-y-2 transition duration-300">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Order Supplies</h4>
                        <p class="text-gray-600 text-sm">Get supplies delivered before your arrival or during your stay.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:-translate-y-2 transition duration-300">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Transportation</h4>
                        <p class="text-gray-600 text-sm">Reliable transport booking to and from the farm.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:-translate-y-2 transition duration-300">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4 text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Secure Payments</h4>
                        <p class="text-gray-600 text-sm">Safe online payments and save your favorite spots.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
