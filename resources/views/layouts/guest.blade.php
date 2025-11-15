<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-green-100 via-green-200 to-green-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-12 px-4">

        <!-- Logo -->
        <div class="mb-6">
            <a href="/">
                <x-application-logo class="w-24 h-24 md:w-28 md:h-28 text-green-700" />
            </a>
        </div>

        <!-- Form container -->
        <div class="w-full sm:max-w-md bg-white backdrop-blur-md bg-opacity-80 shadow-2xl rounded-3xl px-8 py-10 md:px-12 md:py-12">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
