<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Partner Registration - {{ config('app.name', 'Mazraa') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }
        @keyframes slow-zoom { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-15px); } }
        .animate-slow-zoom { animation: slow-zoom 25s ease-out forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
        }
        @keyframes shimmer { 100% { transform: translateX(100%); } }

        /* Custom Scrollbar for right panel */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e2e8f0; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #cbd5e1; }
    </style>
</head>

<body class="bg-white antialiased text-gray-900 relative overflow-x-hidden min-h-screen" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">

    <div class="flex flex-col lg:flex-row w-full min-h-screen">

        {{-- LEFT SIDE: VISUAL BRANDING --}}
        <div class="hidden lg:flex lg:w-[45%] relative overflow-hidden bg-[#020617] sticky top-0 h-screen">
            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=2000&auto=format&fit=crop"
                 alt="Business Wealth Background"
                 class="absolute inset-0 w-full h-full object-cover opacity-[0.4] animate-slow-zoom">
            <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/80 to-transparent"></div>
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#1d5c42]/30 rounded-full blur-[120px] animate-float pointer-events-none z-0"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#c2a265]/20 rounded-full blur-[120px] animate-float pointer-events-none z-0" style="animation-delay: 2s;"></div>

            <div class="relative z-20 flex flex-col justify-end p-12 lg:p-16 w-full h-full pb-24">
                <div class="glass-panel p-10 rounded-[2.5rem] max-w-xl transition-all duration-700 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                    <div class="mb-8 flex items-center gap-4">
                        <div class="p-3.5 bg-white/10 rounded-2xl border border-white/20 shadow-inner backdrop-blur-md">
                            <svg class="w-8 h-8 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tighter">Mazraa<span class="text-[#c2a265]">.com</span></h2>
                    </div>
                    <h1 class="text-5xl font-black text-white mb-6 leading-[1.1] tracking-tight drop-shadow-md">
                        Become a <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-[#c2a265]">
                            Partner.
                        </span>
                    </h1>
                    <p class="text-lg text-gray-300 font-medium leading-relaxed drop-shadow-md">
                        Join the elite network of farm owners. List your property, manage bookings, and increase your revenue securely.
                    </p>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: REGISTRATION FORM --}}
        <div class="w-full lg:w-[55%] flex flex-col relative bg-white min-h-screen">

            {{-- Back to Home Button --}}
            <div class="absolute top-6 left-6 sm:top-8 sm:left-12 z-20">
                <a href="{{ url('/') }}" class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-[#1d5c42] transition-colors duration-300">
                    <div class="p-2 rounded-full bg-gray-50 border border-gray-100 group-hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    <span class="hidden sm:inline">Back to Home</span>
                </a>
            </div>

            <div class="flex-1 flex flex-col px-6 sm:px-12 md:px-16 lg:px-20 xl:px-28 py-24 sm:py-28">

                <div class="mb-10 transition-all duration-700 delay-100 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mb-3">Create Account</h2>
                    <p class="text-base text-gray-500 font-medium">Fill in your details to set up your business portal.</p>
                </div>

                <form method="POST" action="{{ route('partner.register.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-700 delay-200 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        {{-- Name --}}
                        <div class="group">
                            <label for="name" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Full Name</label>
                            <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}"
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-4 px-5 font-medium transition-all duration-300 {{ $errors->has('name') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                placeholder="John Doe">
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-600 font-bold" />
                        </div>

                        {{-- Phone --}}
                        <div class="group">
                            <label for="phone" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Phone Number</label>
                            <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-4 px-5 font-medium transition-all duration-300 {{ $errors->has('phone') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                placeholder="+962 7X XXX XXXX">
                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs text-red-600 font-bold" />
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="group transition-all duration-700 delay-300 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <label for="email" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Email Address</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-4 px-5 font-medium transition-all duration-300 {{ $errors->has('email') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                            placeholder="business@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600 font-bold" />
                    </div>

                    {{-- Passwords --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-700 delay-400 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <div class="group">
                            <label for="password" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Password</label>
                            <input id="password" name="password" type="password" required
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-4 px-5 font-medium transition-all duration-300 {{ $errors->has('password') ? 'border-red-500 ring-4 ring-red-500/20' : '' }}"
                                placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-600 font-bold" />
                        </div>

                        <div class="group">
                            <label for="password_confirmation" class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white focus:ring-4 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-4 px-5 font-medium transition-all duration-300"
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- BANK DETAILS SECTION --}}
                    <div class="pt-6 transition-all duration-700 delay-500 transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <div class="bg-gray-50 rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-inner">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                                    <svg class="w-6 h-6 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">Payout Details</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Where should we send your earnings? <span class="italic">(Optional for now)</span></p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="group">
                                        <label for="bank_name" class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Bank Name</label>
                                        <input id="bank_name" name="bank_name" type="text" value="{{ old('bank_name') }}"
                                            class="block w-full bg-white border border-gray-200 text-gray-900 rounded-xl shadow-sm focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-3.5 px-4 transition-all"
                                            placeholder="e.g. Arab Bank">
                                    </div>
                                    <div class="group">
                                        <label for="account_holder_name" class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">Account Holder</label>
                                        <input id="account_holder_name" name="account_holder_name" type="text" value="{{ old('account_holder_name') }}"
                                            class="block w-full bg-white border border-gray-200 text-gray-900 rounded-xl shadow-sm focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-3.5 px-4 transition-all"
                                            placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="group">
                                    <label for="iban" class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 transition-colors group-focus-within:text-[#1d5c42]">IBAN Number</label>
                                    <input id="iban" name="iban" type="text" value="{{ old('iban') }}"
                                        class="block w-full bg-white border border-gray-200 text-gray-900 rounded-xl shadow-sm focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] sm:text-sm py-3.5 px-4 uppercase tracking-widest font-bold transition-all"
                                        placeholder="JO00 XXXX 0000 0000 0000 0000 0000 00">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Terms Checkbox --}}
                    <div class="flex items-center pt-4 transition-all duration-700 delay-[600ms] transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="w-5 h-5 border border-gray-300 rounded-md bg-gray-50 text-[#1d5c42] focus:ring-[#1d5c42]/30 cursor-pointer transition-all duration-300">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-600 cursor-pointer">
                                I agree to the <a href="#" class="font-bold text-[#1d5c42] hover:text-[#154531] transition-colors border-b border-transparent hover:border-[#154531]">Terms and Conditions</a>
                                and <a href="#" class="font-bold text-[#1d5c42] hover:text-[#154531] transition-colors border-b border-transparent hover:border-[#154531]">Privacy Policy</a>.
                            </label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4 transition-all duration-700 delay-[700ms] transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                        <button type="submit"
                            class="relative w-full flex justify-center py-5 px-4 rounded-2xl text-sm font-black uppercase tracking-widest text-white bg-gradient-to-r from-[#1d5c42] to-[#154531] hover:from-[#154531] hover:to-[#0a2318] shadow-[0_10px_25px_rgba(29,92,66,0.3)] focus:outline-none transition-all duration-300 ease-out overflow-hidden group hover:-translate-y-1 active:translate-y-0 active:scale-[0.98]">
                            <span class="absolute top-0 -left-full w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shimmer_1.5s_infinite]"></span>
                            <span class="relative z-10 flex items-center gap-2">
                                Create Partner Account
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Footer Login Link --}}
                <div class="mt-8 text-center transition-all duration-700 delay-[800ms] transform" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <p class="text-sm font-medium text-gray-500">
                        Already a partner?
                        <a href="{{ route('portal.login') }}" class="font-black text-[#1d5c42] hover:text-[#154531] transition-colors ml-1 border-b-2 border-transparent hover:border-[#1d5c42] pb-0.5">
                            Sign in here
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
