@extends('layouts.transport')

@section('title', 'Fleet Vehicles')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .fade-in-up-stagger { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background: #0891b2; } /* Cyan hover */

    /* Native CSS Pagination */
    .custom-pagination nav { display: flex; align-items: center; gap: 0.5rem; }
    .custom-pagination .page-item .page-link { background-color: #0f172a; border: none; color: #64748b; font-size: 11px; font-weight: 900; padding: 0.75rem 1.25rem; border-radius: 0.75rem; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); }
    .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover { background-color: #0891b2; color: white; }
    .custom-pagination .page-item.active .page-link { background-color: #0891b2; color: white; box-shadow: 0 0 20px rgba(8, 145, 178, 0.4); }
    .custom-pagination .page-item.disabled .page-link { background-color: transparent; opacity: 0.3; color: #334155; cursor: not-allowed; box-shadow: none; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 animate-god-in fade-in-up">

    {{-- 🌟 Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Transport Assets
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Fleet <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-400">Vehicles</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-3">Manage Your Transport Assets and assignments.</p>
        </div>

        <a href="{{ route('transport.vehicles.create') }}" class="relative z-10 w-full md:w-auto px-8 py-5 bg-gradient-to-r from-cyan-600 to-blue-500 hover:to-blue-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(8,145,178,0.3)] transition-all active:scale-95 flex items-center justify-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Vehicle
        </a>
    </div>

    {{-- 🌟 Notifications --}}
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

    {{-- 🌟 Data Table --}}
    <div class="bg-slate-900/60 backdrop-blur-xl rounded-[3rem] border border-slate-800 shadow-2xl overflow-hidden fade-in-up" style="animation-delay: 0.1s;">
        @if($vehicles->count() > 0)
            <div class="overflow-x-auto table-scroll">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-slate-950/80">
                            <th class="px-8 py-6 border-b border-slate-800 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Vehicle Details</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">License Plate</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-center text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Capacity</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-center text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Status / Driver</th>
                            <th class="px-8 py-6 border-b border-slate-800 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($vehicles as $vehicle)
                            <tr class="hover:bg-cyan-500/5 transition-colors duration-300 group">
                                <td class="px-8 py-6 whitespace-nowrap bg-transparent">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-[1.25rem] bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 shadow-inner group-hover:scale-105 transition-transform shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-white font-black text-sm tracking-tight mb-1">{{ $vehicle->type }}</p>
                                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">ID: #VEH-{{ $vehicle->id }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6 whitespace-nowrap bg-transparent">
                                    <span class="bg-slate-950 text-cyan-400 font-mono font-black px-3 py-1.5 rounded-lg text-xs border border-slate-800 uppercase tracking-widest shadow-inner group-hover:border-cyan-500/30 transition-colors">
                                        {{ $vehicle->license_plate }}
                                    </span>
                                </td>

                                <td class="px-8 py-6 whitespace-nowrap text-center bg-transparent">
                                    <div class="inline-flex items-center justify-center bg-slate-950 border border-slate-800 text-white font-black px-3 py-1.5 rounded-lg text-xs shadow-inner">
                                        {{ $vehicle->capacity }} <span class="text-cyan-500 ml-1 text-[9px] uppercase tracking-widest">PAX</span>
                                    </div>
                                </td>

                                <td class="px-8 py-6 whitespace-nowrap text-center bg-transparent">
                                    @if($vehicle->status === 'available')
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Available
                                        </span>
                                    @elseif($vehicle->status === 'maintenance')
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Maintenance
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Booked
                                        </span>
                                    @endif

                                    @if($vehicle->driver)
                                        <p class="text-[9px] font-black text-cyan-400 mt-2 uppercase tracking-widest flex items-center justify-center gap-1.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $vehicle->driver->name }}
                                        </p>
                                    @else
                                        <p class="text-[9px] font-black text-rose-500 mt-2 uppercase tracking-widest bg-rose-500/10 border border-rose-500/20 inline-block px-2 py-0.5 rounded">Unassigned</p>
                                    @endif
                                </td>

                                <td class="px-8 py-6 whitespace-nowrap text-right bg-transparent">
                                    <div class="flex justify-end items-center gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                        <a href="{{ route('transport.vehicles.edit', $vehicle->id) }}" class="flex items-center justify-center p-3 bg-slate-950 text-slate-400 border border-slate-700 hover:border-cyan-500 hover:text-cyan-400 rounded-xl transition-all shadow-inner active:scale-95" title="Edit Vehicle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('transport.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('WARNING: Are you sure you want to remove this vehicle?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center justify-center p-3 bg-slate-950 text-rose-500 border border-slate-700 hover:border-rose-500 hover:bg-rose-600 hover:text-white rounded-xl transition-all shadow-inner active:scale-95" title="Delete Vehicle">
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

            {{-- Pagination --}}
            @if(method_exists($vehicles, 'hasPages') && $vehicles->hasPages())
                <div class="px-8 py-6 border-t border-slate-800 bg-slate-950/50 custom-pagination">
                    {{ $vehicles->links() }}
                </div>
            @elseif(method_exists($vehicles, 'links'))
                <div class="px-8 py-6 border-t border-slate-800 bg-slate-950/50 custom-pagination">
                    {{ $vehicles->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="py-32 text-center flex flex-col items-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-slate-950 border border-slate-800 shadow-inner mb-6">
                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-2 tracking-tight">No Vehicles Yet</h3>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] max-w-sm mx-auto mb-8 leading-relaxed">Add your first vehicle to start building your transport fleet.</p>
                <a href="{{ route('transport.vehicles.create') }}" class="px-8 py-4 bg-gradient-to-r from-cyan-600 to-blue-500 hover:to-blue-400 text-slate-950 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(8,145,178,0.3)] active:scale-95 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Initialize First Vehicle
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
