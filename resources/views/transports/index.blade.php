@extends('layouts.app')

@section('title', 'Transport Requests')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Transport Requests</h1>
                <p class="mt-1 text-gray-500 text-sm">Manage your transportation schedules and logistics.</p>
            </div>

            <a href="{{ route('transports.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-0.5 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Request
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if ($transports->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm text-center">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Requests Found</h3>
                <p class="text-gray-500 mb-6">You haven't created any transport requests yet.</p>
                <a href="{{ route('transports.create') }}" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md transition">
                    Create First Request
                </a>
            </div>
        @else
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Route</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($transports as $transport)
                                <tr class="hover:bg-gray-50/50 transition">

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-blue-50 p-2.5 rounded-lg text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $transport->transport_type }}</p>
                                                <p class="text-xs text-gray-500">{{ $transport->passengers }} Passengers</p>

                                                {{-- نقلنا اسم السائق هنا بما أننا حذفنا عمود الحالة --}}
                                                @if(optional($transport->driver)->name)
                                                    <p class="text-xs text-blue-600 mt-1 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                        Driver: {{ $transport->driver->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                {{ $transport->start_and_return_point }}
                                            </div>
                                            <div class="pl-1 border-l-2 border-gray-200 h-3 ml-1"></div>
                                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                                {{ optional($transport->farm)->name ?? 'Unknown Farm' }}
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1 pl-4">{{ $transport->distance }} km</p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <p class="text-gray-900 font-medium">Arr: {{ $transport->Farm_Arrival_Time }}</p>
                                            <p class="text-gray-500 text-xs">Dep: {{ $transport->Farm_Departure_Time ?? '-' }}</p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <span class="text-lg font-bold text-gray-900">{{ number_format($transport->price, 2) }}</span>
                                        <span class="text-xs font-semibold text-gray-500">JD</span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('transports.edit', $transport->id) }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('transports.destroy', $transport->id) }}" method="POST" onsubmit="return confirm('Delete this request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="md:hidden space-y-4">
                @foreach ($transports as $transport)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ optional($transport->farm)->name ?? 'Unknown Farm' }}</h3>
                                <p class="text-sm text-gray-500">{{ $transport->transport_type }} • {{ $transport->passengers }} Pax</p>
                            </div>
                            <div class="font-bold text-gray-900 text-lg">{{ number_format($transport->price, 2) }} <span class="text-sm font-medium text-gray-500">JD</span></div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="font-medium">From:</span> {{ $transport->start_and_return_point }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-medium">Arr:</span> {{ $transport->Farm_Arrival_Time }}
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            {{-- Action Buttons --}}
                            <div class="flex gap-2 w-full">
                                <a href="{{ route('transports.edit', $transport->id) }}" class="flex-1 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-bold text-center">Edit</a>
                                <form action="{{ route('transports.destroy', $transport->id) }}" method="POST" onsubmit="return confirm('Delete?');" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full py-2 bg-red-50 text-red-600 rounded-lg text-sm font-bold">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
