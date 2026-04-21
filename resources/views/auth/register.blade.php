<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($role) && $role === 'farm_owner' ? 'Partner Registration' : 'Register' }} -
        {{ config('app.name', 'Mazraa') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Advanced Animations */
        @keyframes slow-zoom {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .animate-slow-zoom {
            animation: slow-zoom 25s ease-out forwards;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Premium Glassmorphism */
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
        }

        /* Shimmer Effect for Button */
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        /* Custom Scrollbar for right panel */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #cbd5e1;
        }


        /* إخفاء أي أيقونة عين تأتي مع المكون الجاهز */
        .hide-internal-eye button svg,
        .hide-internal-eye .eye-icon {
            display: none !important;
        }

        /* إذا كانت العين تظهر من المتصفح نفسه (مثل Edge/Chrome) */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>
</head>

@php
    $isPartner = isset($role) && $role === 'farm_owner';

    // 🎨 DYNAMIC THEME SYSTEM
    $textAccent = $isPartner ? 'text-blue-600' : 'text-[#1d5c42]';
    $textAccentHover = $isPartner ? 'hover:text-blue-700' : 'hover:text-[#154230]';
    $borderAccentHover = $isPartner ? 'hover:border-blue-700' : 'hover:border-[#154230]';
    $focusRing = $isPartner
        ? 'focus:ring-blue-600/20 focus:border-blue-600'
        : 'focus:ring-[#1d5c42]/20 focus:border-[#1d5c42]';
    $btnGradient = $isPartner
        ? 'from-blue-600 to-indigo-700 hover:from-indigo-700 hover:to-blue-900 shadow-[0_10px_25px_rgba(37,99,235,0.4)]'
        : 'from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a1c14] shadow-[0_10px_25px_rgba(29,92,66,0.4)]';
    $bgGradientFrom = $isPartner ? 'from-[#020617]' : 'from-[#020617]';
    $bgGradientVia = $isPartner ? 'via-blue-950/80' : 'via-[#0f291e]/80';
    $blob1 = $isPartner ? 'bg-blue-600/20' : 'bg-[#1d5c42]/20';
    $blob2 = $isPartner ? 'bg-indigo-500/20' : 'bg-[#c2a265]/20';
    $iconColor = $isPartner ? 'text-blue-400' : 'text-[#c2a265]';
    $bgImage = $isPartner
        ? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2000&auto=format&fit=crop'
        : asset('backgrounds/home.JPG');

    // Content Logic
    $postUrl = $isPartner ? route('partner.register.store') : route('register');
    $loginUrl = $isPartner ? route('portal.login') : route('login');
    $portalTitle = $isPartner ? 'Partner Registration' : 'Create Account';
    $portalSubtitle = $isPartner
        ? 'Enter your details to create a partner dashboard.'
        : 'Enter your details to access premium farm escapes.';
    $heroTitle = $isPartner ? 'Monetize Your' : 'Grow with';
    $heroSubtitle = $isPartner ? 'Farm Property.' : 'Our Community.';
    $heroDesc = $isPartner
        ? 'Join hundreds of top-tier farms securely managing bookings, dynamic pricing, and direct payouts in one dashboard.'
        : 'Create an account today to seamlessly book premium farm retreats, order essential supplies, and manage your logistics.';
    $badgeText = $isPartner ? 'High Conversion Rates' : 'Join 10,000+ Members';
@endphp

<body class="bg-white antialiased text-gray-900 relative overflow-hidden h-screen" x-data="{ mounted: false }"
    x-init="setTimeout(() => mounted = true, 100)">

    <div class="flex w-full h-full">

        {{-- ==========================================
             LEFT SIDE: VISUAL BRANDING
             ========================================== --}}
        <div class="hidden lg:flex lg:w-[45%] xl:w-[50%] relative overflow-hidden bg-[#020617]">
            <img src="{{ asset('images/home.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover opacity-[0.45] animate-slow-zoom {{ !$isPartner ? 'grayscale-[20%]' : '' }}">
            <div class="absolute inset-0 bg-gradient-to-tr {{ $bgGradientFrom }} {{ $bgGradientVia }} to-transparent">
            </div>
            <div
                class="absolute top-1/4 left-1/4 w-96 h-96 {{ $blob1 }} rounded-full blur-[120px] animate-float pointer-events-none">
            </div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 {{ $blob2 }} rounded-full blur-[120px] animate-float pointer-events-none"
                style="animation-delay: 2s;"></div>

            <div class="absolute top-12 left-12 glass-panel rounded-full px-6 py-2.5 flex items-center gap-3 animate-float"
                style="animation-duration: 4s;">
                <span class="flex h-2.5 w-2.5 relative">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                </span>
                <span class="text-white text-xs font-black uppercase tracking-widest">
                    {{ $badgeText }}
                </span>
            </div>

            <div class="relative z-10 flex flex-col justify-end p-16 w-full h-full pb-24">
                <div class="glass-panel p-10 rounded-[2.5rem] max-w-xl transition-all duration-700 transform"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                    <div class="mb-8 flex items-center gap-4">
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/20 shadow-inner backdrop-blur-md">
                            @if ($isPartner)
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            @endif
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tighter">Mazraa<span
                                class="{{ $iconColor }}">.com</span></h2>
                    </div>

                    <h1 class="text-5xl font-black text-white mb-6 leading-[1.1] tracking-tight drop-shadow-md">
                        {{ $heroTitle }} <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">
                            {{ $heroSubtitle }}
                        </span>
                    </h1>

                    <p class="text-lg text-gray-200 font-medium leading-relaxed mb-8 drop-shadow-md">
                        {{ $heroDesc }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ==========================================
             RIGHT SIDE: REGISTRATION FORM
             ========================================== --}}
        <div
            class="w-full lg:w-[55%] xl:w-[50%] flex flex-col relative bg-white h-full overflow-y-auto custom-scrollbar">

            {{-- Back to Home Button --}}
            <div class="absolute top-8 left-8 sm:left-12 z-20 bg-white/90 backdrop-blur-sm p-1 rounded-full shadow-sm">
                <a href="{{ url('/') }}"
                    class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 {{ $textAccentHover }} transition-colors duration-300">
                    <div
                        class="p-2 rounded-full bg-gray-50 border border-gray-100 group-hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <span class="hidden sm:inline pr-2">Back</span>
                </a>
            </div>

            {{-- Form Container --}}
            <div class="flex-1 flex flex-col justify-center px-8 sm:px-16 lg:px-20 xl:px-28 py-20 min-h-full">

                <div class="mb-10 transition-all duration-700 delay-100 transform"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-3 mt-8">
                        {{ $portalTitle }}
                    </h2>
                    <p class="text-base text-gray-500 font-medium">
                        Already have an account?
                        <a href="{{ $loginUrl }}"
                            class="font-black {{ $textAccent }} {{ $textAccentHover }} transition-colors ml-1 border-b-2 border-transparent {{ $borderAccentHover }} pb-0.5">
                            Sign in instead
                        </a>
                    </p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ $postUrl }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 transition-all duration-700 delay-200 transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        {{-- Full Name Input --}}
                        <div class="group">
                            <label for="name"
                                class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:{{ $textAccent }}">Full
                                Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:{{ $textAccent }} transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="name" name="name" type="text" required autofocus
                                    autocomplete="name"
                                    class="pl-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white {{ $focusRing }} sm:text-base py-4 font-medium transition-all duration-300 {{ $errors->has('name') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                    value="{{ old('name') }}" placeholder="John Doe">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-600 font-bold" />
                        </div>

                        {{-- Phone Input --}}
                        <div class="group">
                            <label for="phone"
                                class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:{{ $textAccent }}">Phone
                                Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:{{ $textAccent }} transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="phone" name="phone" type="tel" required autocomplete="tel"
                                    class="pl-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white {{ $focusRing }} sm:text-base py-4 font-medium transition-all duration-300 {{ $errors->has('phone') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                    value="{{ old('phone') }}" placeholder="+962 7X XXX XXXX">
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs text-red-600 font-bold" />
                        </div>
                    </div>

                    {{-- Email Input --}}
                    <div class="group transition-all duration-700 delay-300 transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <label for="email"
                            class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:{{ $textAccent }}">Email
                            Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:{{ $textAccent }} transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="username" required
                                class="pl-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white {{ $focusRing }} sm:text-base py-4 font-medium transition-all duration-300 {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                value="{{ old('email') }}" placeholder="example@domain.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600 font-bold" />
                    </div>

                    {{-- Passwords Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-700 delay-[400ms] transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <x-password-toggle id="password" name="password" type="signup" color="green" required
                            hide-internal-eye>
                            Password
                        </x-password-toggle>

                        <x-password-toggle id="password_confirmation" name="password_confirmation" type="signup"
                            color="green" required hide-internal-eye>
                            Confirm Password
                        </x-password-toggle>
                    </div>

                    {{-- ==========================================
                         PAYOUT DETAILS (ONLY FOR PARTNERS)
                         ========================================== --}}
                    @if ($isPartner)
                        <div class="pt-4 transition-all duration-700 delay-500 transform"
                            :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                            <div
                                class="bg-gray-50 rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-inner relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 w-24 h-24 bg-blue-100 rounded-bl-full -mr-4 -mt-4 opacity-50">
                                </div>

                                <div class="flex items-center gap-3 mb-6 relative z-10">
                                    <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900">Payout Details</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">Where should we send your earnings?
                                            <span class="italic font-semibold text-blue-600">(Optional for now)</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-5 relative z-10">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="group">
                                            <label for="bank_name"
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">Bank
                                                Name</label>
                                            <input id="bank_name" name="bank_name" type="text"
                                                value="{{ old('bank_name') }}"
                                                class="block w-full bg-white border border-gray-200 text-gray-900 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 sm:text-sm py-3 transition-all"
                                                placeholder="e.g. Arab Bank">
                                        </div>
                                        <div class="group">
                                            <label for="account_holder_name"
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">Account
                                                Holder</label>
                                            <input id="account_holder_name" name="account_holder_name" type="text"
                                                value="{{ old('account_holder_name') }}"
                                                class="block w-full bg-white border border-gray-200 text-gray-900 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 sm:text-sm py-3 transition-all"
                                                placeholder="Full Name as on Bank Account">
                                        </div>
                                    </div>
                                    <div class="group">
                                        <label for="iban"
                                            class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-blue-600">IBAN
                                            Number</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-400 font-bold text-sm">JO</span>
                                            </div>
                                            <input id="iban" name="iban" type="text"
                                                value="{{ old('iban') }}"
                                                class="pl-10 block w-full bg-white border border-gray-200 text-gray-900 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 sm:text-sm py-3 uppercase tracking-widest font-bold transition-all"
                                                placeholder="00 XXXX 0000 0000 0000 0000 00">
                                        </div>
                                        <p
                                            class="text-[10px] text-gray-400 mt-1.5 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                            Your data is encrypted and securely stored.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- ========================================== --}}

                    {{-- Terms Checkbox --}}
                    <div class="flex items-center pt-2 transition-all duration-700 delay-[600ms] transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="w-5 h-5 border border-gray-300 rounded-md bg-gray-50 {{ $isPartner ? 'text-blue-600 focus:ring-blue-600/30' : 'text-[#1d5c42] focus:ring-[#1d5c42]/30' }} cursor-pointer transition-all duration-300">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-600 cursor-pointer">
                                I agree to the
                                <a href="javascript:void(0);"
                                    class="font-bold {{ $textAccent }} {{ $textAccentHover }} transition-colors border-b border-transparent {{ $borderAccentHover }}">Terms
                                    and Conditions</a>
                                and
                                <a href="javascript:void(0);"
                                    class="font-bold {{ $textAccent }} {{ $textAccentHover }} transition-colors border-b border-transparent {{ $borderAccentHover }}">Privacy
                                    Policy</a>.
                            </label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-6 transition-all duration-700 delay-[700ms] transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <button type="submit"
                            class="relative w-full flex justify-center py-5 px-4 rounded-2xl text-sm font-black uppercase tracking-widest text-white bg-gradient-to-r {{ $btnGradient }} focus:outline-none transition-all duration-300 ease-out overflow-hidden group hover:-translate-y-1 active:translate-y-0 active:scale-[0.98]">
                            <span
                                class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shimmer_1.5s_infinite]"></span>
                            <span class="relative z-10 flex items-center gap-2">
                                {{ $isPartner ? 'Create Partner Account' : 'Create Account' }}
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

</html>
