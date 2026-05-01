<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($portal) && $portal ? 'Business Portal' : 'Login' }} - {{ config('app.name', 'Mazraa') }}</title>

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
    $isPortal = $portal ?? false;

    // 🎨 DYNAMIC THEME SYSTEM (Safely hardcoded for Tailwind Compiler)
    $textAccent = $isPortal ? 'text-blue-600' : 'text-[#1d5c42]';
    $textAccentHover = $isPortal ? 'hover:text-blue-700' : 'hover:text-[#154230]';
    $borderAccent = $isPortal ? 'border-blue-600' : 'border-[#1d5c42]';
    $borderAccentHover = $isPortal ? 'hover:border-blue-700' : 'hover:border-[#154230]';
    $focusRing = $isPortal
        ? 'focus:ring-blue-600/20 focus:border-blue-600'
        : 'focus:ring-[#1d5c42]/20 focus:border-[#1d5c42]';
    $btnGradient = $isPortal
        ? 'from-blue-600 to-indigo-700 hover:from-indigo-700 hover:to-blue-900 shadow-[0_10px_25px_rgba(37,99,235,0.4)]'
        : 'from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a1c14] shadow-[0_10px_25px_rgba(29,92,66,0.4)]';
    $bgGradientFrom = $isPortal ? 'from-[#020617]' : 'from-[#020617]';
    $bgGradientVia = $isPortal ? 'via-blue-950/80' : 'via-[#0f291e]/80';
    $blob1 = $isPortal ? 'bg-blue-600/20' : 'bg-[#1d5c42]/20';
    $blob2 = $isPortal ? 'bg-indigo-500/20' : 'bg-[#c2a265]/20';
    $iconColor = $isPortal ? 'text-blue-400' : 'text-[#c2a265]';
    $bgImage = $isPortal
        ? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2000&auto=format&fit=crop'
        : asset('backgrounds/login&register.JPG');
@endphp

<body class="bg-white antialiased text-gray-900 relative overflow-hidden h-screen" x-data="{ mounted: false }"
    x-init="setTimeout(() => mounted = true, 100)">

    <div class="flex w-full h-full">

        {{-- ==========================================
             LEFT SIDE: VISUAL BRANDING (Hidden on Mobile)
             ========================================== --}}
        <div class="hidden lg:flex lg:w-[55%] relative overflow-hidden bg-[#020617]">

            {{-- Animated Background Image (Adjusted for better visibility) --}}
            <img src="{{ asset('images/login&register.jpg') }}" alt="Background"
                class="absolute inset-0 w-full h-full object-cover opacity-50 animate-slow-zoom">

            {{-- Deep Gradient Overlay (Softened so the image shows through) --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/80 to-transparent"></div>

            {{-- Glowing Orbs --}}
            <div
                class="absolute top-1/4 left-1/4 w-96 h-96 {{ $blob1 }} rounded-full blur-[120px] animate-float pointer-events-none">
            </div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 {{ $blob2 }} rounded-full blur-[120px] animate-float pointer-events-none"
                style="animation-delay: 2s;"></div>

            {{-- Floating Badge --}}
            <div class="absolute top-12 left-12 glass-panel rounded-full px-6 py-2.5 flex items-center gap-3 animate-float"
                style="animation-duration: 4s;">
                <span class="flex h-2.5 w-2.5 relative">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                </span>
                <span class="text-white text-xs font-black uppercase tracking-widest">
                    {{ $isPortal ? 'Secure Business Network' : 'Premium Farm Escapes' }}
                </span>
            </div>

            {{-- Content Container --}}
            <div class="relative z-10 flex flex-col justify-end p-16 w-full h-full pb-24">
                <div class="glass-panel p-10 rounded-[2.5rem] max-w-xl transition-all duration-700 transform"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">

                    <div class="mb-8 flex items-center gap-4">
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/20 shadow-inner backdrop-blur-md">
                            @if ($isPortal)
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

                    <h1 class="text-5xl font-black text-white mb-6 leading-[1.1] tracking-tight">
                        {{ $isPortal ? 'Partner' : 'Escape to' }} <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">
                            {{ $isPortal ? 'Portal.' : "Nature's Best." }}
                        </span>
                    </h1>

                    <p class="text-lg text-gray-300 font-medium leading-relaxed">
                        {{ $isPortal
                            ? 'Manage your farm listings, track supply orders, and dispatch transport fleets securely from your centralized dashboard.'
                            : 'About this escape' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ==========================================
             RIGHT SIDE: AUTHENTICATION FORM
             ========================================== --}}
        <div class="w-full lg:w-[45%] flex flex-col relative bg-white h-full overflow-y-auto">

            {{-- Back to Home Button --}}
            <div class="absolute top-8 left-8 sm:left-12 z-20">
                <a href="{{ url('/') }}"
                    class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 hover:{{ $textAccent }} transition-colors duration-300">
                    <div
                        class="p-2 rounded-full bg-gray-50 border border-gray-100 group-hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <span>Back</span>
                </a>
            </div>

            {{-- Form Container --}}
            <div class="flex-1 flex flex-col justify-center px-8 sm:px-16 lg:px-20 xl:px-28 py-20 min-h-full">

                {{-- Mobile Logo (Only visible on small screens) --}}
                <div class="lg:hidden mb-12 flex items-center gap-3 transition-all duration-700 transform"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <div class="bg-gradient-to-tr {{ $btnGradient }} p-2.5 rounded-xl text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </div>
                    <span class="text-3xl font-black tracking-tighter text-gray-900">Mazraa<span
                            class="{{ $textAccent }}">.com</span></span>
                </div>

                {{-- Headers --}}
                <div class="mb-10 transition-all duration-700 delay-100 transform"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-3">
                        {{ $isPortal ? 'Business Login' : 'Welcome Back' }}
                    </h2>
                    <p class="text-base text-gray-500 font-medium">
                        {{ $isPortal ? 'Enter your credentials to access the partner dashboard.' : 'Please enter your details to access your account.' }}
                    </p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ $isPortal ? route('portal.login') : route('login') }}"
                    class="space-y-6">
                    @csrf
                    <input type="hidden" name="portal_login" value="0">
                    <input type="hidden" name="expected_role" value="user">

                    {{-- Email Input --}}
                    <div class="group transition-all duration-700 delay-200 transform"
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
                            <input id="email" name="email" type="email" autocomplete="email" required
                                autofocus
                                class="pl-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white {{ $focusRing }} sm:text-base py-4 font-medium transition-all duration-300 {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                value="{{ old('email') }}" placeholder="example@domain.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 font-bold" />
                    </div>

                    {{-- Password Input --}}
                    <div class="group transition-all duration-700 delay-300 transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        {{-- تأكد أن الحاوية هنا لا تملك margin-top إضافي يخرب المستوى --}}
                        <div class="relative">
                            <x-password-toggle id="password" name="password" type="login" color="green"
                                class="w-full !rounded-none border-slate-700 bg-slate-800/50 text-white font-bold tracking-widest focus:border-rose-500 hide-internal-eye">
                                Password
                            </x-password-toggle>
                        </div>
                        <x-input-error :messages="$errors->get('password')"
                            class="mt-2 text-[10px] text-rose-500 font-bold uppercase tracking-tighter" />
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center pt-2 transition-all duration-700 delay-400 transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <label class="relative inline-flex items-center cursor-pointer group/toggle">
                            <input type="checkbox" name="remember" id="remember_me" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all {{ $isPortal ? 'peer-checked:bg-blue-600 peer-focus:ring-blue-600/30' : 'peer-checked:bg-[#1d5c42] peer-focus:ring-[#1d5c42]/30' }}">
                            </div>
                            <span
                                class="ml-3 text-sm font-bold text-gray-600 group-hover/toggle:text-gray-900 transition-colors">Keep
                                me logged in</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-6 transition-all duration-700 delay-500 transform"
                        :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <button type="submit"
                            class="relative w-full flex justify-center py-5 px-4 rounded-2xl text-sm font-black uppercase tracking-widest text-white bg-gradient-to-r {{ $btnGradient }} focus:outline-none transition-all duration-300 ease-out overflow-hidden group hover:-translate-y-1 active:translate-y-0 active:scale-[0.98]">
                            <span
                                class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shimmer_1.5s_infinite]"></span>
                            <span class="relative z-10 flex items-center gap-2">
                                Log In
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Footer Links --}}
                <div class="mt-12 text-center transition-all duration-700 delay-[600ms] transform"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    @if (!$isPortal)
                        <p class="text-sm font-medium text-gray-500">
                            New to Mazraa?
                            <a href="{{ route('register') }}"
                                class="font-black {{ $textAccent }} {{ $textAccentHover }} transition-colors ml-1 border-b-2 border-transparent {{ $borderAccentHover }} pb-0.5">
                                Create an account
                            </a>
                        </p>
                    @else
                        <p class="text-sm font-medium text-gray-500">
                            Want to list your business?
                            <a href="{{ route('partner.register') }}"
                                class="font-black {{ $textAccent }} {{ $textAccentHover }} transition-colors ml-1 border-b-2 border-transparent {{ $borderAccentHover }} pb-0.5">
                                Partner with us
                            </a>
                        </p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</body>

</html>
