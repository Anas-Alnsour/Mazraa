<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($role) && $role === 'farm_owner' ? 'Partner Registration' : 'Register' }} - {{ config('app.name', 'Mazraa') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .bg-blob { position: absolute; filter: blur(80px); z-index: 0; opacity: 0.4; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-slate-50 relative overflow-hidden h-screen">

    @php
        $isPartner = isset($role) && $role === 'farm_owner';
        $bgFrom = $isPartner ? 'from-slate-950/95' : 'from-green-950/95';
        $bgVia = $isPartner ? 'via-blue-900/80' : 'via-emerald-900/80';
        $accentColor = $isPartner ? 'blue-600' : 'green-500';
        $accentHover = $isPartner ? 'blue-700' : 'emerald-600';
        $textAccent = $isPartner ? 'blue-400' : 'green-400';
        $blobOne = $isPartner ? 'bg-blue-200' : 'bg-green-200';
        $blobTwo = $isPartner ? 'bg-slate-200' : 'bg-emerald-100';
        $btnGradientFrom = $isPartner ? 'from-blue-600' : 'from-green-500';
        $btnGradientTo = $isPartner ? 'to-slate-700' : 'to-emerald-600';

        $postUrl = $isPartner ? route('partner.register') : route('register');
        $loginUrl = $isPartner ? route('portal.login') : route('login');
    @endphp

    <div class="bg-blob {{ $blobOne }} w-96 h-96 rounded-full top-0 right-0 translate-x-1/3 -translate-y-1/3"></div>
    <div class="bg-blob {{ $blobTwo }} w-96 h-96 rounded-full bottom-0 right-1/4 translate-y-1/3"></div>

    <div class="h-screen flex relative z-10">

        <div class="hidden lg:flex lg:w-[45%] xl:w-[50%] relative overflow-hidden bg-gray-900 group">

            <img src="{{ $isPartner ? 'https://images.unsplash.com/photo-1558449028-b53a39d100fc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80' : 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80' }}"
                 alt="Background"
                 class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay transition-transform duration-[20s] ease-linear group-hover:scale-110">

            <div class="absolute inset-0 bg-gradient-to-tr {{ $bgFrom }} {{ $bgVia }} to-transparent"></div>

            <div class="absolute top-12 left-12 glass-panel rounded-full px-6 py-2 flex items-center space-x-3 shadow-2xl animate-bounce" style="animation-duration: 3s;">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $textAccent }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-{{ $textAccent }}"></span>
                </span>
                <span class="text-white text-sm font-medium tracking-wide">
                    {{ $isPartner ? 'High Conversion Rates' : 'Join 10,000+ Members' }}
                </span>
            </div>

            <div class="relative z-10 flex flex-col justify-end p-16 w-full h-full">
                <div class="glass-panel p-10 rounded-[2rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] max-w-xl mb-6 transform transition-all hover:-translate-y-2 duration-500 border-l-4 border-l-{{ $textAccent }}">
                    <div class="mb-8 flex items-center space-x-4">
                        <div class="p-4 bg-gradient-to-br {{ $btnGradientFrom }} {{ $btnGradientTo }} rounded-2xl shadow-lg">
                            @if($isPartner)
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            @else
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            @endif
                        </div>
                        <h2 class="text-3xl font-extrabold text-white tracking-wider">MAZRAA<span class="text-{{ $textAccent }}">.COM</span></h2>
                    </div>
                    <h1 class="text-5xl font-extrabold text-white mb-6 leading-tight">
                        {{ $isPartner ? 'Monetize Your' : 'Grow with' }} <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">
                            {{ $isPartner ? 'Farm Property.' : 'Our Community.' }}
                        </span>
                    </h1>
                    <p class="text-lg text-white/90 leading-relaxed mb-8">
                        {{ $isPartner
                            ? 'Join hundreds of top-tier farms securely managing bookings, dynamic pricing, and direct payouts in one dashboard.'
                            : 'Create an account today to seamlessly book premium farm retreats, order essential supplies, or manage your agricultural logistics.' }}
                    </p>

                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-100">
                            <svg class="w-5 h-5 text-{{ $textAccent }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $isPartner ? 'Automated financial tracking' : 'Instant bookings for top-rated farms' }}
                        </li>
                        <li class="flex items-center text-gray-100">
                            <svg class="w-5 h-5 text-{{ $textAccent }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $isPartner ? 'Powerful interactive booking calendar' : 'Integrated transport & supply services' }}
                        </li>
                        <li class="flex items-center text-gray-100">
                            <svg class="w-5 h-5 text-{{ $textAccent }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $isPartner ? 'Secure role-based dual-portal' : 'Secure and unified dashboard' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[55%] xl:w-[50%] flex flex-col relative px-8 py-8 sm:px-16 lg:px-20 bg-white/60 backdrop-blur-xl h-full overflow-y-auto custom-scrollbar">

            <div class="absolute top-6 left-6 sm:left-10 lg:left-12">
                <a href="{{ url('/') }}" class="group flex items-center space-x-2 text-sm font-semibold text-gray-500 hover:text-{{ $accentColor }} transition-colors duration-300">
                    <div class="p-2 rounded-full bg-white shadow-sm border border-gray-100 group-hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <span class="hidden sm:inline">Back to Home</span>
                </a>
            </div>

            <div class="flex-1 flex flex-col justify-center max-w-lg mx-auto w-full pt-16 lg:pt-0">

                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight mb-2">
                        {{ $isPartner ? 'Partner Registration' : 'Create Account' }}
                    </h2>
                    <p class="text-base text-gray-500 font-medium">
                        Already have an account?
                        <a href="{{ $loginUrl }}" class="font-extrabold text-{{ $accentColor }} hover:text-{{ $accentHover }} transition-colors ml-1 border-b-2 border-transparent hover:border-{{ $accentHover }} pb-0.5">
                            Sign in instead
                        </a>
                    </p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ $postUrl }}" class="space-y-5">
                    @csrf

                    <div class="group">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1.5 transition-colors group-focus-within:text-{{ $accentColor }}">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-{{ $accentColor }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="name" name="name" type="text" required autofocus autocomplete="name"
                                class="pl-12 block w-full bg-white border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-{{ $accentColor }}/20 focus:border-{{ $accentColor }} sm:text-base py-3 transition-all duration-300 {{ $errors->has('name') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                value="{{ old('name') }}" placeholder="Anas Alnsour">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-sm text-red-600 font-medium" />
                    </div>

                    <div class="group">
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5 transition-colors group-focus-within:text-{{ $accentColor }}">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-{{ $accentColor }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="username" required
                                class="pl-12 block w-full bg-white border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-{{ $accentColor }}/20 focus:border-{{ $accentColor }} sm:text-base py-3 transition-all duration-300 {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                value="{{ old('email') }}" placeholder="anas@example.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-sm text-red-600 font-medium" />
                    </div>

                    <div class="group">
                        <label for="phone" class="block text-sm font-bold text-gray-700 mb-1.5 transition-colors group-focus-within:text-{{ $accentColor }}">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-{{ $accentColor }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <input id="phone" name="phone" type="tel" required autocomplete="tel"
                                class="pl-12 block w-full bg-white border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-{{ $accentColor }}/20 focus:border-{{ $accentColor }} sm:text-base py-3 transition-all duration-300 {{ $errors->has('phone') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                value="{{ old('phone') }}" placeholder="0791234567">
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1.5 text-sm text-red-600 font-medium" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="group">
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5 transition-colors group-focus-within:text-{{ $accentColor }}">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-{{ $accentColor }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                    class="pl-12 block w-full bg-white border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-{{ $accentColor }}/20 focus:border-{{ $accentColor }} sm:text-base py-3 transition-all duration-300 {{ $errors->has('password') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-sm text-red-600 font-medium" />
                        </div>

                        <div class="group">
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1.5 transition-colors group-focus-within:text-{{ $accentColor }}">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-{{ $accentColor }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                    class="pl-12 block w-full bg-white border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-{{ $accentColor }}/20 focus:border-{{ $accentColor }} sm:text-base py-3 transition-all duration-300"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-sm text-red-600 font-medium" />
                        </div>
                    </div>

                    <div class="flex items-start pt-2">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-{{ $accentColor }}/30 accent-{{ $accentColor }} cursor-pointer">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-600 cursor-pointer">
                                I agree to the
                                <a href="#" class="text-{{ $accentColor }} hover:text-{{ $accentHover }} hover:underline">Terms and Conditions</a>
                                and
                                <a href="#" class="text-{{ $accentColor }} hover:text-{{ $accentHover }} hover:underline">Privacy Policy</a>.
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="relative w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-[0_8px_20px_-6px_rgba(0,0,0,0.3)] text-base font-extrabold text-white bg-gradient-to-r {{ $btnGradientFrom }} {{ $btnGradientTo }} hover:shadow-lg hover:-translate-y-1 focus:outline-none transition-all duration-300 ease-out overflow-hidden group">
                            <span class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:left-full transition-all duration-700 ease-in-out"></span>
                            {{ $isPartner ? 'Create Partner Account' : 'Create Account' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>
