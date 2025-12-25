{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="relative w-full min-h-[calc(100vh-5rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden bg-gray-900">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('backgrounds/Contact&about.JPG') }}" alt="Background" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-green-900/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">

            <div class="hidden md:flex flex-col justify-center text-white space-y-6 p-6">
                <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-white drop-shadow-sm">
                    Let's Talk
                </h1>
                <p class="text-gray-200 text-lg leading-relaxed">
                    Have questions about booking a farm, or feedback on our services? We are here to help you enjoy the best experience at Mazraa.com.
                </p>

                <div class="space-y-4 mt-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-md">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 uppercase tracking-wider">Email Us</p>
                            <p class="font-semibold">support@mazraa.com</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-md">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 uppercase tracking-wider">Call Us</p>
                            <p class="font-semibold">+962 79 123 4567</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-8 md:p-10 shadow-2xl">
                <h2 class="text-3xl font-bold text-white mb-2 md:hidden">Contact Us</h2>
                <p class="text-gray-300 mb-6 md:hidden">Send us a message and we'll reply soon.</p>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 text-green-100 rounded-xl text-center backdrop-blur-md">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                    @csrf

                    <div class="relative group">
                        <label class="block text-sm font-medium text-gray-300 mb-1 pl-1" for="name">Your Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="John Doe"
                                class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition duration-200" />
                        </div>
                    </div>

                    <div class="relative group">
                        <label class="block text-sm font-medium text-gray-300 mb-1 pl-1" for="email">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="name@example.com"
                                class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition duration-200" />
                        </div>
                    </div>

                    <div class="relative group">
                        <label class="block text-sm font-medium text-gray-300 mb-1 pl-1" for="message">Your Message</label>
                        <div class="relative">
                            <textarea id="message" name="message" rows="4" required placeholder="How can we help you?"
                                class="w-full p-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white/10 transition duration-200"></textarea>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white font-bold text-lg shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5 transition duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 focus:ring-offset-gray-900">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
