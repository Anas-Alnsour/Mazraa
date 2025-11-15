@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="flex justify-center items-center py-16 min-h-[calc(100vh-8rem)]">
    <div class="w-1/2 bg-white rounded-3xl shadow-2xl p-12">
        <h2 class="text-4xl font-extrabold text-green-700 mb-6 text-center">Contact Us</h2>
        <p class="text-gray-500 mb-8 text-center">Have questions or feedback? Send us a message!</p>

        <!-- Success message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="text-gray-700 font-semibold mb-2 block">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div>
                <label for="email" class="text-gray-700 font-semibold mb-2 block">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div>
                <label for="message" class="text-gray-700 font-semibold mb-2 block">Message</label>
                <textarea name="message" id="message" rows="5" required
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md">{{ old('message') }}</textarea>
                <x-input-error :messages="$errors->get('message')" class="mt-1 text-sm text-red-600"/>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl shadow-lg font-semibold transition transform hover:scale-105 mt-4">Send Message</button>
        </form>
    </div>
</div>
@endsection
