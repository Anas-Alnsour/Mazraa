@extends('layouts.supply')

@section('title', 'Register New Operative')

@section('content')
    <style>
        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* God Mode Inputs */
        .supply-input {
            background: rgba(2, 6, 23, 0.6) !important;
            border: 1px solid rgba(51, 65, 85, 0.8) !important;
            color: #f8fafc !important;
            transition: all 0.3s ease !important;
            border-radius: 1rem !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3) !important;
        }

        .supply-input:focus {
            background: rgba(15, 23, 42, 1) !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.3) !important;
            outline: none !important;
        }

        .supply-input::placeholder {
            color: #64748b !important;
            font-weight: 500;
        }

        /* Custom Input Autofill Dark Mode Fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #020617 inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* God Mode Select */
        .dark-select {
            appearance: none;
            background-color: rgba(2, 6, 23, 0.6) !important;
            border: 1px solid rgba(51, 65, 85, 0.8) !important;
            color: #fff !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2310b981' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7l5 5 5-5'/%3e%3c/svg%3e");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            border-radius: 1rem !important;
            transition: all 0.3s ease;
        }

        .dark-select:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important;
            outline: none;
        }

        .dark-select option {
            background-color: #0f172a;
            color: #fff;

            /* كود لجعل أسهم حقل الأرقام بيضاء وتتناسب مع التصميم الغامق */
            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                filter: invert(100%);
                opacity: 0.8;
                /* لكي لا تكون فاقعة جداً، يمكنك تعديلها */
                cursor: pointer;
            }

            /* لمتصفح فايرفوكس */
            input[type="number"] {
                color-scheme: dark;
            }
        }
    </style>

    <div class="max-w-[96%] xl:max-w-4xl mx-auto space-y-10 pb-24 animate-god-in fade-in-up">

        {{-- 🌟 Header Section --}}
        <div
            class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-teal-500/10 blur-[100px] rounded-full pointer-events-none">
            </div>
            <div
                class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none">
            </div>

            <div class="relative z-10">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Network Expansion
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Register <span
                        class="bg-clip-text text-transparent bg-gradient-to-r from-teal-400 to-emerald-400">Operative</span>
                </h1>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-3">Add new personnel to your
                    delivery fleet network.</p>
            </div>

            <a href="{{ route('supplies.drivers.index') }}"
                class="relative z-10 px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-emerald-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-emerald-500/30 flex items-center justify-center gap-3 shadow-inner active:scale-95 group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Return to Fleet
            </a>
        </div>

        {{-- Error Handling --}}
        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-6 shadow-inner backdrop-blur-md">
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0 border border-rose-500/30 text-rose-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-black text-rose-400 uppercase tracking-widest mb-2">Registration Failed</h3>
                        <ul class="list-disc pl-5 font-bold text-sm text-slate-300 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- 🌟 Main Form --}}
        <div
            class="bg-slate-900/60 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <form action="{{ route('supplies.drivers.store') }}" method="POST" class="space-y-10 relative z-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Identity Section --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Operative
                            Full Name <span class="text-emerald-500">*</span></label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                placeholder="e.g. Ahmad Khaled" class="w-full supply-input pl-12 py-4 font-bold text-sm">
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Email
                            Address <span class="text-emerald-500">*</span></label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                placeholder="operative@network.com"
                                class="w-full supply-input pl-12 py-4 font-bold font-mono text-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Phone
                            Number <span class="text-emerald-500">*</span></label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                placeholder="07xxxxxxxx"
                                class="w-full supply-input pl-12 py-4 font-bold font-mono tracking-widest text-sm">
                        </div>
                    </div>
                </div>

                {{-- Logistics Assignment --}}
                <div class="pt-6 border-t border-slate-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="md:col-span-2 space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2 flex items-center gap-2">
                                Assigned Shift
                                <span
                                    class="text-[8px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded uppercase tracking-widest border border-slate-700">Dispatch
                                    Routing</span>
                            </label>
                            <div class="relative z-20">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 z-10">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <select name="shift" id="shift" required
                                    class="w-full dark-select pl-12 py-4 font-bold text-sm">
                                    <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>☀️ Morning
                                        Shift (08:00 AM - 05:00 PM)</option>
                                    <option value="evening" {{ old('shift') == 'evening' ? 'selected' : '' }}>🌙 Evening
                                        Shift (07:00 PM - 06:00 AM)</option>
                                    {{-- <option value="full_day" {{ old('shift') == 'full_day' ? 'selected' : '' }}>🕒 Full Day
                                        Availability</option> --}}
                                </select>
                            </div>
                        </div>
                    </div>


                    {{-- Vehicle Information --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Vehicle Type --}}
                        <div class="md:col-span-2 space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Vehicle
                                Type <span class="text-cyan-500">*</span></label>
                            <select name="type" id="type" required
                                class="w-full dark-select px-6 py-5 font-bold text-sm">
                                <option value="" disabled selected>Select vehicle classification...</option>
                                <option value="Mini Van (7 Seats)"
                                    {{ old('type') == 'Mini Van (7 Seats)' ? 'selected' : '' }}>Mini Van (7 Seats)</option>
                                <option value="Van (12 Seats)" {{ old('type') == 'Van (12 Seats)' ? 'selected' : '' }}>Van
                                    (12 Seats)</option>
                                <option value="Coaster Bus (22 Seats)"
                                    {{ old('type') == 'Coaster Bus (22 Seats)' ? 'selected' : '' }}>Coaster Bus (22 Seats)
                                </option>
                                <option value="Large Bus (50 Seats)"
                                    {{ old('type') == 'Large Bus (50 Seats)' ? 'selected' : '' }}>Large Bus (50 Seats)
                                </option>
                                <option value="SUV / 4x4" {{ old('type') == 'SUV / 4x4' ? 'selected' : '' }}>SUV / 4x4
                                </option>
                                <option value="Sedan (VIP)" {{ old('type') == 'Sedan (VIP)' ? 'selected' : '' }}>Sedan
                                    (VIP)</option>
                            </select>
                        </div>

                        {{-- License Plate --}}
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">License
                                Plate Number <span class="text-cyan-500">*</span></label>
                            <input type="text" name="license_plate" id="license_plate"
                                value="{{ old('license_plate') }}" required placeholder="e.g. 10-12345"
                                class="w-full bg-slate-800/50 border border-slate-700 text-white placeholder:text-slate-500 transport-input px-6 py-5 font-black uppercase font-mono tracking-widest text-sm placeholder:normal-case focus:border-cyan-500 focus:outline-none transition-all">
                        </div>

                        {{-- Capacity --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">
                                Passenger Capacity <span class="text-cyan-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="capacity" id="capacity" min="1"
                                    value="{{ old('capacity') }}" required placeholder="e.g. 12"
                                    class="w-full bg-slate-800/50 border border-slate-700 text-white placeholder:text-slate-500 transport-input px-6 py-5 font-black uppercase font-mono tracking-widest text-sm placeholder:normal-case focus:border-cyan-500 focus:outline-none transition-all">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-12 pointer-events-none text-slate-500 font-black text-[10px] uppercase tracking-widest">
                                    PAX
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 🌟 Status Matrix
                    <div class="pt-8 mt-8 border-t border-slate-800">
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 ml-2">Initial
                            Status Matrix</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="relative">
                                <input type="radio" name="status" id="status_available" value="available"
                                    class="status-radio sr-only"
                                    {{ old('status', 'available') === 'available' ? 'checked' : '' }}>
                                <label for="status_available"
                                    class="flex flex-col items-center justify-center p-6 border border-slate-700 bg-slate-950 rounded-[1.5rem] cursor-pointer transition-all hover:border-emerald-500/50 group shadow-inner">
                                    <span
                                        class="w-3 h-3 rounded-full bg-emerald-500 mb-3 shadow-[0_0_10px_#10b981] group-hover:scale-125 transition-transform"></span>
                                    <span
                                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white">Available</span>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="status" id="status_maintenance" value="maintenance"
                                    class="status-radio sr-only" {{ old('status') === 'maintenance' ? 'checked' : '' }}>
                                <label for="status_maintenance"
                                    class="flex flex-col items-center justify-center p-6 border border-slate-700 bg-slate-950 rounded-[1.5rem] cursor-pointer transition-all hover:border-amber-500/50 group shadow-inner">
                                    <span
                                        class="w-3 h-3 rounded-full bg-amber-500 mb-3 shadow-[0_0_10px_#f59e0b] group-hover:scale-125 transition-transform"></span>
                                    <span
                                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white">Maintenance</span>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="status" id="status_booked" value="booked"
                                    class="status-radio sr-only" {{ old('status') === 'booked' ? 'checked' : '' }}>
                                <label for="status_booked"
                                    class="flex flex-col items-center justify-center p-6 border border-slate-700 bg-slate-950 rounded-[1.5rem] cursor-pointer transition-all hover:border-blue-500/50 group shadow-inner">
                                    <span
                                        class="w-3 h-3 rounded-full bg-blue-500 mb-3 shadow-[0_0_10px_#3b82f6] group-hover:scale-125 transition-transform"></span>
                                    <span
                                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white">Booked</span>
                                </label>
                            </div>
                        </div>
                    </div> --}}



                    {{-- Security Protocol --}}
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8 p-6 md:p-8 bg-slate-950/50 rounded-[2rem] border border-slate-800 shadow-inner">
                        <div
                            class="md:col-span-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#020617] border border-slate-800 text-[9px] font-black text-rose-400 uppercase tracking-widest w-max mb-2">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Security Protocol
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">
                                Access Password <span class="text-rose-500">*</span>
                            </label>

                            <div class="relative group">
                                <input type="password" name="password" id="password" required
                                    class="w-full bg-slate-800/50 border border-slate-700 text-white supply-input px-5 py-4 pr-12 font-bold tracking-widest focus:border-rose-500 focus:outline-none transition-all">

                                <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-white hover:text-rose-500 transition-colors">
                                    <svg id="eye_icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <script>
                            function togglePassword() {
                                const passwordInput = document.getElementById('password');
                                const eyeIcon = document.getElementById('eye_icon');

                                if (passwordInput.type === 'password') {
                                    passwordInput.type = 'text';
                                    // تغيير شكل الأيقونة عند الإظهار (اختياري: إضافة خط فوق العين)
                                    eyeIcon.innerHTML =
                                        '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
                                } else {
                                    passwordInput.type = 'password';
                                    // إعادة شكل العين الأصلية
                                    eyeIcon.innerHTML =
                                        '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
                                }
                            }
                        </script>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">
                                Verify Password <span class="text-rose-500">*</span>
                            </label>

                            <div class="relative group">
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full bg-slate-800/50 border border-slate-700 text-white supply-input px-5 py-4 pr-12 font-bold tracking-widest focus:border-rose-500 focus:outline-none transition-all">

                                <button type="button" onclick="toggleVerifyPassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-white hover:text-rose-500 transition-colors">
                                    <svg id="eye_icon_verify" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <script>
                            // الدالة الخاصة بحقل التأكيد فقط
                            function toggleVerifyPassword() {
                                const confirmInput = document.getElementById('password_confirmation');
                                const eyeIconVerify = document.getElementById('eye_icon_verify');

                                if (confirmInput.type === 'password') {
                                    confirmInput.type = 'text';
                                    eyeIconVerify.innerHTML =
                                        '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
                                } else {
                                    confirmInput.type = 'password';
                                    eyeIconVerify.innerHTML =
                                        '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
                                }
                            }
                        </script>
                    </div>


                    {{-- Action Buttons --}}
                    <div class="pt-8 mt-8 flex flex-col md:flex-row justify-end gap-4 border-t border-slate-800">
                        <button type="submit"
                            class="w-full md:w-auto py-4 px-12 bg-gradient-to-r from-teal-600 to-emerald-500 hover:to-emerald-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(16,185,129,0.3)] transition-all active:scale-95 flex items-center justify-center gap-3">
                            Initialize Driver
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
            </form>
        </div>
    </div>
@endsection
