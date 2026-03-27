<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Owner Portal - {{ config('app.name', 'Mazraa') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .bg-blob { position: absolute; filter: blur(80px); z-index: 0; opacity: 0.4; }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-slate-50 relative overflow-hidden">
    @php
        $bgFrom = 'from-slate-900'; $bgVia = 'via-slate-800';
        $accentColor = 'blue-600'; $accentHover = 'blue-700'; $textAccent = 'blue-400';
        $blobOne = 'bg-blue-200'; $blobTwo = 'bg-slate-200';
        $btnGradientFrom = 'from-blue-600'; $btnGradientTo = 'to-slate-700';
        $portalTitle = 'Business Login';
        $portalSubtitle = 'Enter your credentials to access the partner dashboard.';
        $heroTitle = 'Partner'; $heroSubtitle = 'Portal.';
        $heroDesc = 'Manage your farm listings, track supply orders, and dispatch transport fleets securely.';
        $badgeText = 'Secure Business Network';
        $bgImage = 'https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80';
        $postRoute = route('portal.login');
    @endphp

    <div class="bg-blob {{ $blobOne }} w-96 h-96 rounded-full top-0 right-0 translate-x-1/3 -translate-y-1/3"></div>
    <div class="bg-blob {{ $blobTwo }} w-96 h-96 rounded-full bottom-0 right-1/4 translate-y-1/3"></div>

    <div class="min-h-screen flex relative z-10">
        <div class="hidden lg:flex lg:w-[55%] relative overflow-hidden bg-gray-900 group">
            <img src="{{ $bgImage }}" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay transition-transform duration-[20s] ease-linear group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-tr {{ $bgFrom }} {{ $bgVia }} to-transparent"></div>
            <div class="absolute top-12 left-12 glass-panel rounded-full px-6 py-2 flex items-center space-x-3 shadow-2xl animate-bounce" style="animation-duration: 3s;">
                <span class="flex h-3 w-3 relative"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $textAccent }} opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-{{ $textAccent }}"></span></span>
                <span class="text-white text-sm font-medium tracking-wide">{{ $badgeText }}</span>
            </div>
            <div class="relative z-10 flex flex-col justify-end p-16 w-full h-full">
                <div class="glass-panel p-10 rounded-[2rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] max-w-xl mb-6 transform transition-all hover:-translate-y-2 duration-500 border-l-4 border-l-{{ $textAccent }}">
                    <div class="mb-8 flex items-center space-x-4">
                        <div class="p-4 bg-gradient-to-br {{ $btnGradientFrom }} {{ $btnGradientTo }} rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-white tracking-wider">MAZRAA<span class="text-{{ $textAccent }}">.COM</span></h2>
                    </div>
                    <h1 class="text-5xl font-extrabold text-white mb-6 leading-tight">{{ $heroTitle }} <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">{{ $heroSubtitle }}</span></h1>
                    <p class="text-lg text-white/90 leading-relaxed mb-8">{{ $heroDesc }}</p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[45%] flex flex-col relative px-8 py-10 sm:px-16 lg:px-20 xl:px-28 bg-white/60 backdrop-blur-xl">
            <div class="absolute top-8 left-8 sm:left-12 lg:left-16">
                <a href="{{ url('/') }}" class="group flex items-center space-x-2 text-sm font-semibold text-gray-500 hover:text-{{ $accentColor }} transition-colors duration-300">
                    <div class="p-2 rounded-full bg-white shadow-sm border border-gray-100 group-hover:bg-gray-50 transition-colors"><svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></div>
                    <span>Back to Home</span>
                </a>
            </div>

            <div class="flex-1 flex flex-col justify-center mt-12 lg:mt-0">
                <div class="mb-12 text-center lg:text-left">
                    <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight mb-3">{{ $portalTitle }}</h2>
                    <p class="text-base text-gray-500 font-medium">{{ $portalSubtitle }}</p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ $postRoute }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="portal_login" value="1">

                    <div class="group">
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2 transition-colors group-focus-within:text-{{ $accentColor }}">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400 group-focus-within:text-{{ $accentColor }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                            <input id="email" name="email" type="email" required autofocus class="pl-12 block w-full bg-white border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-{{ $accentColor }}/20 focus:border-{{ $accentColor }} sm:text-base py-3.5 transition-all duration-300" placeholder="admin@mazraa.com">
                        </div>
                    </div>

                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-gray-700 transition-colors group-focus-within:text-{{ $accentColor }}">Password</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400 group-focus-within:text-{{ $accentColor }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
                            <input id="password" name="password" type="password" required class="pl-12 block w-full bg-white border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-{{ $accentColor }}/20 focus:border-{{ $accentColor }} sm:text-base py-3.5 transition-all duration-300" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="relative w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-[0_8px_20px_-6px_rgba(37,99,235,0.5)] text-base font-extrabold text-white bg-gradient-to-r {{ $btnGradientFrom }} {{ $btnGradientTo }} hover:shadow-lg hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-300 ease-out overflow-hidden group">
                            <span class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:left-full transition-all duration-700 ease-in-out"></span>
                            Sign In to Dashboard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
