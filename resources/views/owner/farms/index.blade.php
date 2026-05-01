<x-owner-layout>
    <x-slot name="header">Asset Directory</x-slot>

    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

        /* Custom Pagination Styling */
        .custom-pagination nav { display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .custom-pagination .page-item .page-link { background-color: #0f172a; border: none; color: #64748b; font-size: 11px; font-weight: 900; padding: 0.75rem 1.25rem; border-radius: 0.75rem; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); }
        .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover { background-color: #10b981; color: white; }
        .custom-pagination .page-item.active .page-link { background-color: #10b981; color: white; box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); }
        .custom-pagination .page-item.disabled .page-link { background-color: transparent; opacity: 0.3; color: #334155; cursor: not-allowed; box-shadow: none; }
    </style>

    <div class="space-y-10 pb-24 animate-god-in fade-in-up">

        {{-- 🌟 Premium Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden backdrop-blur-2xl transition-all hover:border-emerald-500/30">
            <div class="absolute -left-20 -top-20 w-80 h-80 bg-[#1d5c42]/20 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                <div class="w-16 h-16 rounded-[1.5rem] bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.2)] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner mx-auto md:mx-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Network Assets
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-1">My <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Properties</span></h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-2">Manage, update, and Add luxury estates.</p>
                </div>
            </div>

            <div class="relative z-10 w-full md:w-auto mt-6 md:mt-0 flex justify-center md:justify-end">
                <a href="{{ route('owner.farms.create') }}" class="w-full md:w-auto px-8 py-5 bg-gradient-to-tr from-emerald-600 to-emerald-400 hover:to-emerald-300 text-slate-950 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_30px_rgba(16,185,129,0.3)] transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Add New Farm
                </a>
            </div>
        </div>

        {{-- 🌟 Assets Grid --}}
        @if($farms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($farms as $farm)
                    <div class="group bg-slate-900/60 rounded-[3rem] overflow-hidden border border-slate-800 hover:border-emerald-500/40 shadow-2xl hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)] transition-all duration-500 flex flex-col h-full relative backdrop-blur-xl">

                        {{-- Image Section (Framed & Refined) --}}
                        <div class="relative h-64 w-full bg-slate-950 p-2 shrink-0">
                            <div class="relative w-full h-full rounded-[2.5rem] overflow-hidden border border-slate-800/80 shadow-inner">
                                @if($farm->main_image)
                                    <img src="{{ asset('storage/' . $farm->main_image) }}" alt="{{ $farm->name }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-900">
                                        <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/20 to-transparent opacity-90"></div>

                                {{-- Smart Status Badge --}}
                                <div class="absolute top-4 right-4 z-10">
                                    @if($farm->is_approved === 1)
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[9px] font-black bg-emerald-500/20 text-emerald-400 backdrop-blur-md border border-emerald-500/30 uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_5px_#34d399]"></span> Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black bg-amber-500/20 text-amber-500 backdrop-blur-md border border-amber-500/30 uppercase tracking-widest shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Pending Review
                                        </span>
                                    @endif
                                </div>

                                {{-- Meta Badge --}}
                                <div class="absolute bottom-4 left-4 z-10 flex gap-2">
                                    <span class="px-3 py-1.5 bg-slate-950/80 backdrop-blur-md text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-lg border border-slate-700/50 shadow-lg">ID #{{ str_pad($farm->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Content Body --}}
                        <div class="p-6 md:p-8 flex-1 flex flex-col">
                            <h2 class="text-2xl font-black text-white tracking-tight line-clamp-1 mb-2 group-hover:text-emerald-400 transition-colors">{{ $farm->name }}</h2>

                            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-[#c2a265] pl-3">
                                <svg class="w-4 h-4 text-[#c2a265] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path></svg>
                                {{ $farm->location ?? $farm->governorate }}
                            </div>

                            {{-- 🌟 3-Column Rates Grid 🌟 --}}
                            <div class="grid grid-cols-3 gap-2 py-5 border-y border-slate-800/80 mt-auto mb-8 bg-slate-950/40 rounded-2xl px-4 shadow-inner">

                                {{-- Morning Shift --}}
                                <div class="flex flex-col justify-center text-center sm:text-left pr-2">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-center sm:justify-start gap-1">
                                        <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="hidden sm:inline">Morning</span>
                                        <span class="sm:hidden">AM</span>
                                    </p>
                                    <p class="text-base sm:text-lg font-black text-emerald-400 tracking-tighter">{{ number_format($farm->price_per_morning_shift, 0) }}<span class="text-[8px] text-slate-500 font-bold uppercase tracking-widest ml-0.5 hidden sm:inline">JOD</span></p>
                                </div>

                                {{-- Evening Shift --}}
                                <div class="flex flex-col justify-center text-center sm:text-left border-l border-slate-800 pl-2 sm:pl-4 pr-2">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-center sm:justify-start gap-1">
                                        <svg class="w-3 h-3 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                        <span class="hidden sm:inline">Evening</span>
                                        <span class="sm:hidden">PM</span>
                                    </p>
                                    <p class="text-base sm:text-lg font-black text-indigo-400 tracking-tighter">{{ number_format($farm->price_per_evening_shift, 0) }}<span class="text-[8px] text-slate-500 font-bold uppercase tracking-widest ml-0.5 hidden sm:inline">JOD</span></p>
                                </div>

                                {{-- Full Day --}}
                                <div class="flex flex-col justify-center text-center sm:text-left border-l border-slate-800 pl-2 sm:pl-4">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5 flex items-center justify-center sm:justify-start gap-1">
                                        <svg class="w-3 h-3 text-[#c2a265] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="hidden sm:inline">Full Day</span>
                                        <span class="sm:hidden">24H</span>
                                    </p>
                                    <p class="text-base sm:text-lg font-black text-[#c2a265] tracking-tighter">{{ number_format($farm->price_per_full_day, 0) }}<span class="text-[8px] text-slate-500 font-bold uppercase tracking-widest ml-0.5 hidden sm:inline">JOD</span></p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-3">
                                <a href="{{ route('owner.farms.edit', $farm->id) }}" class="flex-1 py-4 bg-slate-950 hover:bg-slate-800 text-slate-300 hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl text-center border border-slate-800 shadow-sm active:scale-95 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Configure
                                </a>
                                <form action="{{ route('owner.farms.destroy', $farm->id) }}" method="POST" class="w-auto flex-shrink-0" onsubmit="return confirm('WARNING: This will purge the asset from the ecosystem. Proceed?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-5 py-4 bg-slate-950 hover:bg-rose-600 text-rose-500 hover:text-white border border-slate-800 hover:border-rose-500 rounded-xl transition-all shadow-sm active:scale-95 flex items-center justify-center" title="Purge Asset">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 🌟 Pagination --}}
            @if(method_exists($farms, 'hasPages') && $farms->hasPages())
                <div class="mt-14 flex justify-center custom-pagination pb-8">
                    {{ $farms->links() }}
                </div>
            @endif

        @else
            {{-- 🌟 Empty State --}}
            <div class="py-32 text-center bg-slate-900/40 rounded-[3rem] border border-slate-800 border-dashed flex flex-col items-center shadow-inner relative overflow-hidden">
                <div class="w-28 h-28 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-8 shadow-inner border border-slate-800 relative z-10">
                    <svg class="w-14 h-14 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-3 tracking-tight relative z-10">No Assets Deployed</h3>
                <p class="text-[11px] font-bold text-slate-500 max-w-sm uppercase tracking-widest leading-relaxed relative z-10">You haven't initialized any farms in your portfolio. Deploy a new asset to accept reservations.</p>
                <a href="{{ route('owner.farms.create') }}" class="mt-8 px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-[0_10px_20px_rgba(16,185,129,0.2)] transition-all active:scale-95 relative z-10">
                    Initialize Setup
                </a>
            </div>
        @endif
    </div>
</x-owner-layout>
