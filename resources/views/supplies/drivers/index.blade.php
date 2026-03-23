@extends('layouts.supply')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Delivery Fleet</h1>
            <p class="text-sm font-bold text-emerald-600 tracking-widest uppercase mt-1">Manage Your Delivery Personnel</p>
        </div>
        <a href="{{ route('supplies.drivers.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Driver
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 animate-fade-in flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        @if($drivers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver Details</th>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact Info</th>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined Date</th>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($drivers as $driver)
                            <tr class="hover:bg-emerald-50/30 transition-colors duration-200">
                                <td class="px-8 py-6 bg-transparent">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-lg uppercase shadow-sm">
                                            {{ substr($driver->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-gray-900 font-black text-base">{{ $driver->name }}</p>
                                            <p class="text-[10px] font-bold text-emerald-600 mt-1 uppercase tracking-widest bg-emerald-50 inline-block px-2 py-0.5 rounded-md">Supply Driver</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 bg-transparent">
                                    <p class="text-sm font-black text-gray-900">{{ $driver->phone ?? 'N/A' }}</p>
                                    <p class="text-[11px] font-bold text-gray-400 mt-1">{{ $driver->email }}</p>
                                </td>
                                <td class="px-8 py-6 bg-transparent">
                                    <p class="text-sm font-black text-gray-900">{{ $driver->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="px-8 py-6 bg-transparent text-right">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('supplies.drivers.edit', $driver->id) }}" class="inline-flex items-center justify-center px-4 py-2 border-2 border-gray-100 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 bg-white hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-100 transition-all transform active:scale-95 shadow-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('supplies.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this driver?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border-2 border-gray-100 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 bg-white hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all transform active:scale-95 shadow-sm">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($drivers->hasPages())
                <div class="px-8 py-5 border-t border-gray-100 bg-gray-50">
                    {{ $drivers->links() }}
                </div>
            @endif
        @else
            <div class="px-8 py-16 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-1">No Drivers in Fleet</h3>
                <p class="text-sm font-bold text-gray-400 mb-6">You haven't added any delivery drivers yet. Add your first driver to start dispatching orders.</p>
                <a href="{{ route('supplies.drivers.create') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-8 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 uppercase tracking-widest text-xs">
                    Add First Driver
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
