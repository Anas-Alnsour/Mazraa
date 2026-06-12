@extends('layouts.transport')

@section('title', 'Fleet Personnel Core')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

    /* Custom Pagination Styling Overrides */
    .custom-pagination nav { display: flex; align-items: center; gap: 0.5rem; }
    .custom-pagination .page-item .page-link { background-color: #0f172a; border: none; color: #64748b; font-size: 11px; font-weight: 900; padding: 0.75rem 1.25rem; border-radius: 0.75rem; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); }
    .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover { background-color: #3b82f6; color: white; }
    .custom-pagination .page-item.active .page-link { background-color: #3b82f6; color: white; box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
    .custom-pagination .page-item.disabled .page-link { background-color: transparent; opacity: 0.3; color: #334155; cursor: not-allowed; box-shadow: none; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 animate-god-in fade-in-up">

    {{-- 🌟 1. Header Section (Ultra-Modern) --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden backdrop-blur-2xl transition-all hover:border-blue-500/30">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            <div class="w-16 h-16 rounded-[1.5rem] bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shadow-[0_0_20px_rgba(59,130,246,0.2)] shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 shadow-inner mx-auto md:mx-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Transport Division
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-1">Fleet <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">Personnel</span></h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-2">Manage dispatch agents and node communications.</p>
            </div>
        </div>

        <a href="{{ route('transport.drivers.create') }}" class="relative z-10 w-full md:w-auto px-8 py-5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:to-cyan-400 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(59,130,246,0.3)] transition-all active:scale-95 flex items-center justify-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Agent
        </a>
    </div>

    {{-- 🌟 2. Notifications --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-[2rem] p-5 shadow-inner backdrop-blur-md flex items-center gap-4 fade-in-up">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0 border border-emerald-500/30 text-emerald-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-[11px] font-black text-emerald-400 uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-5 shadow-inner backdrop-blur-md flex items-center gap-4 fade-in-up">
            <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0 border border-rose-500/30 text-rose-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <p class="text-[11px] font-black text-rose-400 uppercase tracking-widest">{{ session('error') }}</p>
        </div>
    @endif

    {{-- 🌟 3. Drivers Matrix Table --}}
    <div class="bg-slate-900/60 backdrop-blur-xl rounded-[3rem] border border-slate-800 shadow-2xl overflow-hidden fade-in-up" style="animation-delay: 0.1s;">
        @if($drivers->count() > 0)
            <div class="overflow-x-auto table-scroll w-full">
                <table class="min-w-full w-full leading-normal text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/80">
                            <th class="px-8 py-6 border-b border-slate-800 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] whitespace-nowrap">Agent Identity</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] whitespace-nowrap">Comms Protocol</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] whitespace-nowrap">Region (Zone)</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-center text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] whitespace-nowrap">Activation Date</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($drivers as $driver)
                            <tr class="hover:bg-blue-500/5 transition-colors duration-300 group">

                                {{-- Agent Identity --}}
                                <td class="px-8 py-6 whitespace-nowrap bg-transparent">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-[1.25rem] bg-slate-950 border border-slate-700 flex items-center justify-center text-blue-400 font-black text-xl shadow-inner group-hover:border-blue-500/50 group-hover:shadow-[0_0_15px_rgba(59,130,246,0.2)] transition-all uppercase shrink-0">
                                            {{ substr($driver->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-white group-hover:text-blue-400 transition-colors truncate tracking-tight mb-1">{{ $driver->name }}</p>
                                            <div class="flex flex-col gap-1">
                                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">ID: #TRP-{{ str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                {{-- الشفت --}}
                                                <p class="text-[9px] font-bold uppercase tracking-widest flex items-center gap-1 {{ $driver->shift === 'morning' ? 'text-amber-400/90' : 'text-indigo-400/90' }}">
                                                    @if($driver->shift === 'morning')
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                        Morning Shift
                                                    @else
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                                        Evening Shift
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Comms Protocol --}}
                                <td class="px-8 py-6 whitespace-nowrap bg-transparent">
                                    <p class="text-xs font-black text-slate-200 tracking-widest mb-1.5 font-mono group-hover:text-white transition-colors flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $driver->phone ?? 'NO COMMS' }}
                                    </p>
                                    <p class="text-[9px] font-bold text-slate-500 truncate max-w-[200px] flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $driver->email }}
                                    </p>
                                </td>

                                {{-- Region --}}
                                <td class="px-8 py-6 whitespace-nowrap bg-transparent">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-950 text-slate-400 font-black text-[9px] border border-slate-800 uppercase tracking-widest shadow-inner group-hover:border-blue-500/30 transition-colors">
                                        {{ $driver->governorate ?? 'Unassigned' }}
                                    </span>
                                </td>

                                {{-- Activation Date --}}
                                <td class="px-8 py-6 whitespace-nowrap text-center bg-transparent">
                                    <p class="text-[11px] font-black text-slate-300">{{ $driver->created_at->format('M d, Y') }}</p>
                                </td>

                                {{-- Actions --}}
                                <td class="px-8 py-6 whitespace-nowrap text-right bg-transparent">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                        <a href="{{ route('transport.drivers.edit', $driver->id) }}" class="flex items-center justify-center p-3 bg-slate-950 text-slate-400 border border-slate-700 hover:border-blue-500 hover:text-blue-400 rounded-xl transition-all shadow-inner active:scale-95" title="Configure Agent">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('transport.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('WARNING: Terminate this agent from the fleet?');" class="inline-block m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center justify-center p-3 bg-slate-950 text-rose-500 border border-slate-700 hover:border-rose-500 hover:bg-rose-600 hover:text-white rounded-xl transition-all active:scale-95 shadow-inner" title="Terminate Agent">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($drivers, 'hasPages') && $drivers->hasPages())
                <div class="px-8 py-6 border-t border-slate-800 bg-slate-950/50 custom-pagination">
                    {{ $drivers->links() }}
                </div>
            @elseif(method_exists($drivers, 'links'))
                <div class="px-8 py-6 border-t border-slate-800 bg-slate-950/50 custom-pagination">
                    {{ $drivers->links() }}
                </div>
            @endif

        @else
            {{-- 🌟 Empty State --}}
            <div class="py-32 text-center flex flex-col items-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-slate-950 border border-slate-800 shadow-inner mb-6">
                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Roster Empty</h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] max-w-sm mx-auto mb-8 leading-relaxed">No delivery agents active in your sector. Deploy an agent to begin dispatching orders.</p>
                <a href="{{ route('transport.drivers.create') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:to-cyan-400 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(59,130,246,0.3)] active:scale-95 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Initialize First Agent
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
