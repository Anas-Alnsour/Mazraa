{{-- 💡 THE FIX: Changed to layouts.transport to get the Sidebar UI --}}
@extends('layouts.transport')

@section('title', 'Fleet Vehicles')

@section('content')
<div class="px-6 py-8 md:px-10">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Fleet Vehicles</h1>
            <p class="text-xs font-bold text-cyan-600 tracking-widest uppercase mt-2">Manage Your Transport Assets</p>
        </div>

        <div class="flex gap-4 shrink-0">
            <a href="{{ route('transport.vehicles.create') }}" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white font-black py-3 px-6 rounded-xl transition-all shadow-lg shadow-cyan-600/30 border border-cyan-700 transform active:scale-95 text-[11px] uppercase tracking-widest">
                <svg class="w-4 h-4 text-cyan-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Add New Vehicle
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl shadow-sm font-bold mb-8 flex items-center gap-3">
            <div class="bg-emerald-500 rounded-full p-1"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-xl shadow-sm font-bold mb-8 flex items-center gap-3">
            <div class="bg-rose-500 rounded-full p-1"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg></div>
            {{ session('error') }}
        </div>
    @endif

    {{-- Vehicles Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle Details</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">License Plate</th>
                        <th scope="col" class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Capacity</th>
                        <th scope="col" class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status / Driver</th>
                        <th scope="col" class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-50">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            {{-- 1. Details --}}
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-cyan-50 border border-cyan-100 rounded-xl flex items-center justify-center text-cyan-600 shadow-inner">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-slate-900 font-black text-sm">{{ $vehicle->type }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">ID: #VEH-{{ $vehicle->id }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Plate --}}
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="bg-slate-100 text-slate-800 font-mono font-bold px-3 py-1.5 rounded-lg text-xs border border-slate-200 uppercase tracking-wider">
                                    {{ $vehicle->license_plate }}
                                </span>
                            </td>

                            {{-- 3. Capacity --}}
                            <td class="px-8 py-6 whitespace-nowrap text-center">
                                <div class="inline-flex items-center justify-center bg-cyan-50 border border-cyan-100 text-cyan-700 font-black px-3 py-1.5 rounded-lg text-xs shadow-sm">
                                    {{ $vehicle->capacity }} <span class="text-cyan-400 ml-1 text-[9px] uppercase tracking-widest">PAX</span>
                                </div>
                            </td>

                            {{-- 4. Status & Driver --}}
                            <td class="px-8 py-6 whitespace-nowrap text-center">
                                @if($vehicle->status === 'available')
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Available
                                    </span>
                                @elseif($vehicle->status === 'maintenance')
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Maintenance
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Booked
                                    </span>
                                @endif

                                @if($vehicle->driver)
                                    <p class="text-[10px] font-bold text-cyan-600 mt-2 uppercase tracking-widest flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ $vehicle->driver->name }}
                                    </p>
                                @else
                                    <p class="text-[10px] font-bold text-rose-500 mt-2 uppercase tracking-widest">Unassigned</p>
                                @endif
                            </td>

                            {{-- 5. Actions --}}
                            <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('transport.vehicles.edit', $vehicle->id) }}" class="text-cyan-600 hover:text-cyan-900 font-black uppercase tracking-widest text-[10px] transition-colors bg-cyan-50 hover:bg-cyan-100 px-3 py-1.5 rounded-lg border border-cyan-100">Edit</a>
                                    <form action="{{ route('transport.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to remove this vehicle?');" class="text-rose-600 hover:text-rose-900 font-black uppercase tracking-widest text-[10px] transition-colors bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg border border-rose-100">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 bg-white text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-50 rounded-full mb-5 border border-slate-100 shadow-inner">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-800 mb-1">No Vehicles Yet</h3>
                                <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400">Add your first vehicle to start building your fleet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($vehicles->hasPages())
            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
