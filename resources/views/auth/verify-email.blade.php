<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Email - {{ config('app.name', 'Mazraa') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }

        /* Advanced Animations */
        @keyframes slow-zoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-slow-zoom { animation: slow-zoom 25s ease-out forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }

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
            100% { transform: translateX(100%); }
        }
    </style>
</head>

<body class="bg-white antialiased text-gray-900 relative overflow-hidden h-screen" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">

    <div class="flex w-full h-full">

        {{-- ==========================================
             LEFT SIDE: VISUAL BRANDING
             ========================================== --}}
        <div class="hidden lg:flex lg:w-[45%] xl:w-[50%] relative overflow-hidden bg-[#020617]">

            {{-- Animated Background Image --}}
            <img src="{{ asset('backgrounds/home.JPG') }}" alt="Luxury Farm Retreat"
                 class="absolute inset-0 w-full h-full object-cover opacity-50 animate-slow-zoom mix-blend-overlay grayscale-[20%]">

            {{-- Deep Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-tr from-[#020617] via-[#0f291e]/80 to-transparent"></div>

            {{-- Glowing Orbs --}}
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#1d5c42]/20 rounded-full blur-[120px] animate-float pointer-events-none z-0"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#c2a265]/20 rounded-full blur-[120px] animate-float pointer-events-none z-0" style="animation-delay: 2s;"></div>

            {{-- Floating Badge --}}
            <div class="absolute top-12 left-12 glass-panel rounded-full px-6 py-2.5 flex items-center gap-3 animate-float z-10" style="animation-duration: 4s;">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                </span>
                <span class="text-white text-xs font-black uppercase tracking-widest">
                    Account Security
                </span>
            </div>

            {{-- Content Container --}}
            <div class="relative z-20 flex flex-col justify-end p-16 w-full h-full pb-24">
                <div class="glass-panel p-10 rounded-[2.5rem] max-w-xl transition-all duration-700 transform"
                     :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">

                    <div class="mb-8 flex items-center gap-4">
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/20 shadow-inner backdrop-blur-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tighter">Mazraa<span class="text-[#c2a265]">.com</span></h2>
                    </div>

                    <h1 class="text-5xl font-black text-white mb-6 leading-[1.1] tracking-tight drop-shadow-md">
                        Almost <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">
                            There.
                        </span>
                    </h1>

                    <p class="text-lg text-gray-200 font-medium leading-relaxed drop-shadow-md">
                        Protecting your account is our top priority. Please verify your email address to unlock all premium features.
                    </p>
                </div>
            </div>
        </div>

        {{-- ==========================================
             RIGHT SIDE: VERIFICATION INFO & ACTIONS
             ========================================== --}}
        <div class="w-full lg:w-[55%] xl:w-[50%] flex flex-col relative bg-white h-full overflow-y-auto">

            {{-- Form Container --}}
            <div class="flex-1 flex flex-col justify-center px-8 sm:px-16 lg:px-20 xl:px-28 py-20 min-h-full">

                {{-- Mobile Logo --}}
                <div class="lg:hidden flex justify-center mb-10 transition-all duration-700 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <div class="p-4 bg-gradient-to-tr from-[#1d5c42] to-[#154230] rounded-2xl shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Headers --}}
                <div class="mb-8 text-center lg:text-left transition-all duration-700 delay-100 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-4">
                        Verify Email
                    </h2>
                    <div class="p-5 bg-[#1d5c42]/5 border border-[#1d5c42]/10 rounded-2xl">
                        <p class="text-sm text-gray-600 font-medium leading-relaxed">
                            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                        </p>
                    </div>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3 transition-all duration-700 delay-200 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-bold text-green-700">
                            A new verification link has been sent to the email address you provided during registration.
                        </p>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 transition-all duration-700 delay-300 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">

                    {{-- Resend Button --}}
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto flex-1">
                        @csrf
                        <button type="submit"
                            class="relative w-full flex justify-center py-4 px-6 rounded-2xl text-xs font-black uppercase tracking-widest text-white bg-gradient-to-r from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a1c14] shadow-[0_8px_20px_rgba(29,92,66,0.3)] focus:outline-none transition-all duration-300 ease-out overflow-hidden group hover:-translate-y-1 active:translate-y-0 active:scale-[0.98]">
                            <span class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shimmer_1.5s_infinite]"></span>
                            <span class="relative z-10 flex items-center gap-2">
                                Resend Verification Email
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </button>
                    </form>

                    {{-- Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full py-4 px-6 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-500 bg-gray-50 hover:bg-gray-100 hover:text-red-500 border border-gray-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Log Out
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</body>
</html>
