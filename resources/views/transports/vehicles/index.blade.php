@extends('layouts.transport')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Fleet Vehicles</h1>
            <p class="text-sm font-bold text-blue-600 tracking-widest uppercase mt-1">Manage Your Transport Assets</p>
        </div>
        <a href="{{ route('transport.vehicles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Vehicle Details</th>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">License Plate</th>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Capacity</th>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6 bg-transparent">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-black text-md">{{ $vehicle->type }}</p>
                                        <p class="text-xs font-bold text-gray-400 mt-0.5 uppercase tracking-widest">ID: #VEH-{{ $vehicle->id }}</p>

                                        @if($vehicle->driver)
                                            <p class="text-[11px] font-bold text-blue-600 mt-1 uppercase tracking-widest flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $vehicle->driver->name }}
                                            </p>
                                        @else
                                            <p class="text-[11px] font-bold text-red-500 mt-1 uppercase tracking-widest">Unassigned</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 bg-transparent">
                                <span class="bg-gray-100 text-gray-800 font-mono font-bold px-4 py-2 rounded-xl text-sm border border-gray-200 uppercase tracking-wider">
                                    {{ $vehicle->license_plate }}
                                </span>
                            </td>
                            <td class="px-8 py-6 bg-transparent text-center">
                                <div class="inline-flex items-center justify-center bg-blue-50 text-blue-700 font-black px-4 py-2 rounded-xl text-sm">
                                    {{ $vehicle->capacity }} <span class="text-blue-400 ml-1 text-[10px] uppercase tracking-widest">PAX</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 bg-transparent text-center">
                                @if($vehicle->status === 'available')
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Available
                                    </span>
                                @elseif($vehicle->status === 'maintenance')
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Maintenance
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Booked
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 bg-transparent text-right">
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ route('transport.vehicles.edit', $vehicle->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('transport.vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 bg-white text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-50 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-gray-800 mb-1">No Vehicles Yet</h3>
                                <p class="text-gray-500 font-medium text-sm">Add your first vehicle to start building your fleet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicles->hasPages())
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
