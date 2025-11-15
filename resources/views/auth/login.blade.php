<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex justify-center items-center py-16 min-h-[calc(100vh-8rem)]">
    <div class="w-1/2 bg-white rounded-3xl shadow-2xl p-12">
        <h2 class="text-4xl font-extrabold text-green-700 mb-6 text-center">Login</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="text-gray-700 font-semibold mb-2 block">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div>
                <label for="password" class="text-gray-700 font-semibold mb-2 block">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full px-5 py-3 border border-green-300 rounded-2xl shadow-sm focus:ring-3 focus:ring-green-300 focus:border-green-500 transition duration-300 hover:shadow-md"/>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600"/>
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center text-gray-600 text-sm">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                    <span class="ml-2">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 hover:text-green-700 underline" href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl shadow-lg font-semibold transition transform hover:scale-105 mt-4">Login</button>
        </form>

        <p class="mt-6 text-gray-500 text-sm text-center">
            Don't have an account? <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 underline">Register</a>
        </p>
    </div>
</div>
@endsection
