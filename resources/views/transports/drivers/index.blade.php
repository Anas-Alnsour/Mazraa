@extends('layouts.transport')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Fleet Drivers</h1>
            <p class="text-sm font-bold text-blue-600 tracking-widest uppercase mt-1">Manage Your Transport Team</p>
        </div>
        <a href="{{ route('transport.drivers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Driver
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
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver Info</th>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined Date</th>
                        <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drivers as $driver)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-8 py-6 bg-transparent">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-black text-xl">
                                        {{ strtoupper(substr($driver->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-900 font-bold text-md">{{ $driver->name }}</p>
                                        <p class="text-xs text-gray-400 font-medium">ID: #TRP-{{ $driver->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 bg-transparent">
                                <p class="text-gray-900 font-bold text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $driver->email }}
                                </p>
                                <p class="text-gray-500 font-medium text-sm mt-1 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $driver->phone }}
                                </p>
                            </td>
                            <td class="px-8 py-6 bg-transparent text-sm">
                                <span class="bg-gray-100 text-gray-600 font-bold px-3 py-1 rounded-full text-xs">
                                    {{ $driver->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-8 py-6 bg-transparent text-right">
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ route('transport.drivers.edit', $driver->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('transport.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this driver?');" class="inline">
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
                            <td colspan="4" class="px-8 py-16 bg-white text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-50 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-gray-800 mb-1">No Drivers Yet</h3>
                                <p class="text-gray-500 font-medium text-sm">Add your first driver to start assigning trips.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($drivers->hasPages())
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100">
                {{ $drivers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
