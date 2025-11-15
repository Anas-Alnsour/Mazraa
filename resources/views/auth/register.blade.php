<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="flex justify-center items-center py-16 min-h-[calc(100vh-8rem)]">
    <div class="w-1/2 bg-white rounded-3xl shadow-2xl p-12">
        <h2 class="text-4xl font-extrabold text-green-700 mb-6 text-center">Register</h2>
        <p class="text-gray-500 mb-8 text-center">Create your account to manage transport requests efficiently</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="text-gray-700 font-semibold mb-2 block">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div>
                <label for="email" class="text-gray-700 font-semibold mb-2 block">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div>
                <label for="password" class="text-gray-700 font-semibold mb-2 block">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div>
                <label for="password_confirmation" class="text-gray-700 font-semibold mb-2 block">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between mt-6">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-green-700 underline mb-3 md:mb-0 text-center md:text-left">Already registered?</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl shadow-lg font-semibold transition w-full md:w-auto">Register</button>
            </div>
        </form>
    </div>
</div>
@endsection
