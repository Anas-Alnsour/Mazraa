<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password - {{ config('app.name', 'Mazraa') }}</title>

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
                    Security First
                </span>
            </div>

            {{-- Content Container --}}
            <div class="relative z-20 flex flex-col justify-end p-16 w-full h-full pb-24">
                <div class="glass-panel p-10 rounded-[2.5rem] max-w-xl transition-all duration-700 transform"
                     :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">

                    <div class="mb-8 flex items-center gap-4">
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/20 shadow-inner backdrop-blur-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tighter">Mazraa<span class="text-[#c2a265]">.com</span></h2>
                    </div>

                    <h1 class="text-5xl font-black text-white mb-6 leading-[1.1] tracking-tight drop-shadow-md">
                        Create New <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#f4e4c1] to-[#c2a265]">
                            Password.
                        </span>
                    </h1>

                    <p class="text-lg text-gray-200 font-medium leading-relaxed drop-shadow-md">
                        Your security is our priority. Set a strong password to continue accessing premium farm escapes and seamless bookings.
                    </p>
                </div>
            </div>
        </div>

        {{-- ==========================================
             RIGHT SIDE: RESET PASSWORD FORM
             ========================================== --}}
        <div class="w-full lg:w-[55%] xl:w-[50%] flex flex-col relative bg-white h-full overflow-y-auto">

            {{-- Form Container --}}
            <div class="flex-1 flex flex-col justify-center px-8 sm:px-16 lg:px-20 xl:px-28 py-20 min-h-full">

                {{-- Mobile Logo --}}
                <div class="lg:hidden flex justify-center mb-10 transition-all duration-700 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <div class="p-4 bg-gradient-to-tr from-[#1d5c42] to-[#154230] rounded-2xl shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Headers --}}
                <div class="mb-10 text-center lg:text-left transition-all duration-700 delay-100 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-3">
                        Reset Password
                    </h2>
                    <p class="text-base text-gray-500 font-medium leading-relaxed">
                        Please enter your email and choose a new, secure password for your account.
                    </p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email Input (Readonly recommended, but kept editable as default Laravel) --}}
                    <div class="group transition-all duration-700 delay-200 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <label for="email" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#1d5c42] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="username" required autofocus
                                class="pl-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-base py-4 font-medium transition-all duration-300 {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                value="{{ old('email', $request->email) }}" placeholder="you@example.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 font-bold" />
                    </div>

                    {{-- Passwords Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 transition-all duration-700 delay-300 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">

                        <div class="group">
                            <label for="password" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">New Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#1d5c42] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                    class="pl-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-base py-4 font-medium transition-all duration-300 {{ $errors->has('password') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 font-bold" />
                        </div>

                        <div class="group">
                            <label for="password_confirmation" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#1d5c42] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                    class="pl-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-base py-4 font-medium transition-all duration-300"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600 font-bold" />
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-6 transition-all duration-700 delay-400 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <button type="submit"
                            class="relative w-full flex justify-center py-5 px-4 rounded-2xl text-sm font-black uppercase tracking-widest text-white bg-gradient-to-r from-[#1d5c42] to-[#154230] hover:from-[#154230] hover:to-[#0a1c14] shadow-[0_10px_25px_rgba(29,92,66,0.4)] focus:outline-none transition-all duration-300 ease-out overflow-hidden group hover:-translate-y-1 active:translate-y-0 active:scale-[0.98]">
                            <span class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shimmer_1.5s_infinite]"></span>
                            <span class="relative z-10 flex items-center gap-2">
                                Save & Login
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>
