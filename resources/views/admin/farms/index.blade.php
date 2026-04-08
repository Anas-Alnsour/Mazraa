@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/50 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Manage <span class="text-emerald-400">Farms</span></h1>
            <p class="text-slate-400 font-medium">Audit, verify, and manage all luxury farm listings.</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.farms.create') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-2xl transition-all shadow-xl shadow-emerald-900/20 flex items-center gap-2 group transform active:scale-95">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Add New Farm
            </a>
        </div>
    </div>

    {{-- Farms Table --}}
    <div class="bg-slate-800/30 rounded-[2.5rem] border border-slate-700/50 overflow-hidden backdrop-blur-md shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-700">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Farm Details</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Location & Stats</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Inquiry Price</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($farms as $farm)
                        <tr class="group hover:bg-slate-700/30 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-6">
                                    <div class="h-20 w-32 rounded-2xl overflow-hidden border-2 border-slate-700 group-hover:border-emerald-500/50 transition-all shadow-lg">
                                        <img src="{{ $farm->main_image ? asset('storage/' . $farm->main_image) : 'https://via.placeholder.com/400x300' }}" class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-black text-white group-hover:text-emerald-400 transition-colors text-lg">{{ $farm->name }}</p>
                                        <p class="text-xs text-slate-500 font-medium">ID: #{{ str_pad($farm->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-2 text-slate-300 font-bold text-sm">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ Str::limit($farm->location, 30) }}
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                                            Status: {{ ucfirst($farm->status) }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-700/50 text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-600/50">
                                            Rating: {{ $farm->rating ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <p class="text-xl font-black text-emerald-400">{{ number_format($farm->price_per_night ?? 0, 2) }} <span class="text-[10px] text-slate-500 uppercase tracking-widest">JOD</span></p>
                                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">Per Night</p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('admin.farms.edit', $farm->id) }}" class="p-2.5 bg-slate-800 hover:bg-emerald-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-all shadow-xl" title="Edit Farm">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.farms.destroy', $farm->id) }}" method="POST" onsubmit="return confirm('Archive this farm permanently?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-all shadow-xl" title="Delete Permanent">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="h-24 w-24 rounded-full bg-slate-800 flex items-center justify-center text-slate-700 border border-slate-700 shadow-inner">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-300">No Farms Active</h3>
                                    <p class="text-slate-500 max-w-xs font-medium">Start by adding your first farm listing to the ecosystem.</p>
                                    <a href="{{ route('admin.farms.create') }}" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-xl transition-all shadow-lg">Initialize First Farm</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($farms->hasPages())
            <div class="px-8 py-8 bg-slate-800/50 border-t border-slate-700 flex justify-center text-white">
                {{ $farms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
