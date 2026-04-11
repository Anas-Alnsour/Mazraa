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
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 animate-god-in">

    {{-- 🌟 1. Header Section (Ultra-Modern) --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-8 md:p-12 rounded-[3rem] border border-slate-800 shadow-2xl relative overflow-hidden backdrop-blur-2xl transition-all hover:border-blue-500/30 fade-in-up">
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

        <div class="relative z-10 w-full md:w-auto mt-6 md:mt-0 flex justify-center md:justify-end gap-3">
            <a href="{{ route('transport.drivers.create') }}" class="w-full md:w-auto px-8 py-5 bg-gradient-to-tr from-blue-600 to-cyan-500 hover:to-cyan-400 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_30px_rgba(59,130,246,0.3)] transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Deploy New Agent
            </a>
        </div>
    </div>

    {{-- 🌟 2. Floating Toast Notifications --}}
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" class="fixed top-24 right-5 z-[150] flex flex-col gap-4 pointer-events-none">
        @if(session('success'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-emerald-500/40 rounded-[2rem] shadow-[0_20px_50px_rgba(16,185,129,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1.5 bg-emerald-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-emerald-500/20 p-3 rounded-xl text-emerald-400 shrink-0 border border-emerald-500/30 shadow-inner"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="flex-1 mt-1"><h4 class="font-black text-white text-xs uppercase tracking-[0.2em]">Execution Success</h4><p class="text-slate-400 text-[11px] mt-1.5 font-medium leading-relaxed">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
        @if(session('error'))
            <div x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10" class="pointer-events-auto bg-slate-900/95 backdrop-blur-2xl border border-rose-500/40 rounded-[2rem] shadow-[0_20px_50px_rgba(244,63,94,0.2)] p-5 flex items-start gap-4 w-96 relative overflow-hidden" x-cloak>
                <div class="absolute bottom-0 left-0 h-1.5 bg-rose-500 w-full animate-[progress-shrink_6s_linear_forwards]"></div>
                <div class="bg-rose-500/20 p-3 rounded-xl text-rose-400 shrink-0 border border-rose-500/30 shadow-inner"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div class="flex-1 mt-1"><h4 class="font-black text-white text-xs uppercase tracking-[0.2em]">System Alert</h4><p class="text-slate-400 text-[11px] mt-1.5 font-medium leading-relaxed">{{ session('error') }}</p></div>
                <button @click="show = false" class="text-slate-600 hover:text-white transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>
    <style>@keyframes progress-shrink { from { width: 100%; } to { width: 0%; } }</style>

    {{-- 🌟 3. Drivers Matrix Table --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.15s;">
        @if($drivers->count() > 0)
            <div class="w-full overflow-x-auto table-scroll bg-slate-900/20">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead class="bg-slate-950/80 border-b border-slate-800">
                        <tr>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Agent Identity</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Comms Protocol</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Region (Zone)</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-center">Activation Date</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-right">Actions Matrix</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40">
                        @foreach($drivers as $driver)
                            <tr class="hover:bg-white/5 transition-colors group">

                                {{-- Agent Identity --}}
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-950 border border-slate-700 flex items-center justify-center text-blue-400 font-black text-xl shadow-inner group-hover:border-blue-500/50 group-hover:shadow-[0_0_15px_rgba(59,130,246,0.2)] transition-all uppercase shrink-0">
                                            {{ substr($driver->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-base font-black text-white group-hover:text-blue-400 transition-colors truncate tracking-tight mb-1">{{ $driver->name }}</p>
                                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest font-mono">ID: #TRP-{{ str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Comms Protocol --}}
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <p class="text-sm font-black text-slate-200 tracking-widest mb-1.5 font-mono group-hover:text-white transition-colors flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $driver->phone ?? 'NO COMMS' }}
                                    </p>
                                    <p class="text-[9px] font-bold text-slate-500 truncate max-w-[200px] flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $driver->email }}
                                    </p>
                                </td>

                                {{-- Region --}}
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-950 text-slate-400 font-black text-[9px] border border-slate-800 uppercase tracking-widest shadow-inner group-hover:border-blue-500/30 transition-colors">
                                        {{ $driver->governorate ?? 'Unassigned Zone' }}
                                    </span>
                                </td>

                                {{-- Activation Date --}}
                                <td class="px-10 py-6 whitespace-nowrap text-center">
                                    <p class="text-xs font-black text-slate-300">{{ $driver->created_at->format('M d, Y') }}</p>
                                </td>

                                {{-- Actions --}}
                                <td class="px-10 py-6 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                        <a href="{{ route('transport.drivers.edit', $driver->id) }}" class="p-3.5 bg-slate-950 text-slate-400 border border-slate-700 hover:border-blue-500 hover:text-blue-400 rounded-xl transition-all shadow-inner active:scale-95" title="Configure Agent">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('transport.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('WARNING: Terminate this agent from the fleet?');" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-3.5 bg-slate-950 text-rose-500 border border-slate-700 hover:border-rose-500 hover:bg-rose-600 hover:text-white rounded-xl transition-all active:scale-95 shadow-inner" title="Terminate Agent">
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
                <div class="px-10 py-10 border-t border-slate-800 bg-slate-950/40 flex flex-col md:flex-row items-center justify-between gap-6 shrink-0">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">
                        Displaying <span class="text-blue-400">{{ $drivers->count() }}</span> of <span class="text-white">{{ $drivers->total() }}</span> agents
                    </p>
                    <div class="custom-pagination">
                        {{ $drivers->links() }}
                    </div>
                </div>
            @endif

        @else
            {{-- 🌟 Empty State (Zero Data) --}}
            <div class="py-32 text-center bg-slate-900/40 border-dashed border-0 border-t border-slate-800 flex flex-col items-center">
                <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner border border-slate-800 relative z-10">
                    <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-2 tracking-tight">Roster Empty</h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] max-w-sm mx-auto mb-8 leading-relaxed">No delivery agents active in your sector. Deploy an agent to begin dispatching orders.</p>
                <a href="{{ route('transport.drivers.create') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(59,130,246,0.3)] active:scale-95 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Initialize First Agent
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    /* Pagination Overrides */
    .custom-pagination nav { @apply flex items-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-900 border-none text-slate-500 text-[11px] font-black px-5 py-3 rounded-xl transition-all hover:bg-blue-600 hover:text-white shadow-lg; }
    .custom-pagination .active .page-link { @apply bg-blue-600 text-white shadow-[0_0_20px_rgba(59,130,246,0.4)]; }
    .custom-pagination .disabled .page-link { @apply bg-transparent opacity-20 text-slate-700 cursor-not-allowed; }
</style>
@endsection
