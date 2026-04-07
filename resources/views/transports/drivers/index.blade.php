@extends('layouts.transport')

@section('title', 'Fleet Drivers')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Fleet Drivers</h1>
            <p class="text-sm font-bold text-cyan-600 tracking-widest uppercase mt-1">Manage Your Transport Team</p>
        </div>
        <a href="{{ route('transport.drivers.create') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white font-black py-3 px-6 rounded-2xl shadow-lg hover:shadow-xl shadow-cyan-600/30 transition-all flex items-center gap-2 transform active:scale-95 text-xs uppercase tracking-widest">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Driver
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 flex items-center gap-3">
            <div class="bg-emerald-500 rounded-full p-1"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 border-b border-slate-100 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver Info</th>
                        <th class="px-8 py-5 border-b border-slate-100 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact</th>
                        <th class="px-8 py-5 border-b border-slate-100 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Region (Governorate)</th>
                        <th class="px-8 py-5 border-b border-slate-100 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Joined Date</th>
                        <th class="px-8 py-5 border-b border-slate-100 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($drivers as $driver)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                            <td class="px-8 py-6 bg-transparent">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-cyan-50 border border-cyan-100 rounded-xl flex items-center justify-center text-cyan-600 font-black text-xl shadow-inner">
                                        {{ strtoupper(substr($driver->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-slate-900 font-black text-sm">{{ $driver->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">ID: #TRP-{{ $driver->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 bg-transparent">
                                <p class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $driver->email }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $driver->phone }}
                                </p>
                            </td>
                            <td class="px-8 py-6 bg-transparent">
                                <span class="bg-slate-100 border border-slate-200 text-slate-700 font-black px-3 py-1.5 rounded-lg text-[10px] uppercase tracking-widest">
                                    {{ $driver->governorate ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 bg-transparent text-center">
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $driver->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-8 py-6 bg-transparent text-right">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('transport.drivers.edit', $driver->id) }}" class="text-cyan-600 hover:text-cyan-900 font-black uppercase tracking-widest text-[10px] transition-colors bg-cyan-50 hover:bg-cyan-100 px-3 py-1.5 rounded-lg border border-cyan-100" title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('transport.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this driver?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 font-black uppercase tracking-widest text-[10px] transition-colors bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg border border-rose-100" title="Delete">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 bg-white text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-50 rounded-full mb-5 border border-slate-100 shadow-inner">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-800 mb-1">No Drivers Yet</h3>
                                <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400">Add your first driver to start assigning trips.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($drivers->hasPages())
            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100">
                {{ $drivers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
