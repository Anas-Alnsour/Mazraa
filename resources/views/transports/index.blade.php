@extends('layouts.app')

@section('title', 'Transport Requests')

@section('content')
<div class="max-w-full mx-auto py-8 px-4">

    <div class="flex flex-col items-center justify-center mb-6 space-y-4">
        <h1 class="text-3xl font-bold text-green-800 text-center">Transport Requests</h1>

        <a href="{{ route('transports.create') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 hover:shadow-lg transition transform hover:scale-105">
            Add New Transport
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4 shadow text-center">
            {{ session('success') }}
        </div>
    @endif

    @if ($transports->isEmpty())
        <p class="text-gray-600 text-center">No transport requests yet.</p>
    @else
        <div class="w-full overflow-hidden">
            <table class="w-full table-fixed border border-gray-200 rounded-xl shadow-lg">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white text-sm md:text-base">
                        <th class="p-4 text-center font-semibold">Type</th>
                        <th class="p-4 text-center font-semibold">Passengers</th>
                        <th class="p-4 text-center font-semibold">Driver</th>
                        <th class="p-4 text-center font-semibold">Start & Return Point</th>
                        <th class="p-4 text-center font-semibold">The Farm</th>
                        <th class="p-4 text-center font-semibold">Distance (km)</th>
                        <th class="p-4 text-center font-semibold">Price</th>
                        <th class="p-4 text-center font-semibold">Farm Arrival</th>
                        <th class="p-4 text-center font-semibold">Farm Departure</th>
                        <th class="p-4 text-center font-semibold">Status</th>
                        <th class="p-4 text-center font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm md:text-base">
                    @foreach ($transports as $transport)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 text-center whitespace-nowrap">{{ $transport->transport_type }}</td>
                        <td class="p-4 text-center whitespace-nowrap">{{ $transport->passengers }}</td>
                        <td class="p-4 text-center whitespace-nowrap">{{ optional($transport->driver)->name ?? '-' }}</td>
                        <td class="p-4 text-center whitespace-nowrap">{{ $transport->start_and_return_point }}</td>
                        <td class="p-4 text-center whitespace-nowrap">{{ optional($transport->farm)->name ?? '-' }}</td>
                        <td class="p-4 text-center whitespace-nowrap">{{ $transport->distance }}</td>
                        <td class="p-4 text-center font-semibold text-green-700 whitespace-nowrap">${{ number_format($transport->price, 2) }}</td>
                        <td class="p-4 text-center whitespace-nowrap">{{ $transport->Farm_Arrival_Time }}</td>
                        <td class="p-4 text-center whitespace-nowrap">{{ $transport->Farm_Departure_Time ?? '-' }}</td>

                        <td class="p-4 text-center whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'in transit' => 'bg-blue-100 text-blue-700'
                                ];
                                $colorClass = $statusColors[strtolower($transport->status)] ?? 'bg-gray-200 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 text-sm rounded-full font-medium {{ $colorClass }}">
                                {{ ucfirst($transport->status) }}
                            </span>
                        </td>

                        <td class="p-4 text-center whitespace-nowrap">
                            <div class="flex justify-center gap-2 flex-wrap">
                                <a href="{{ route('transports.edit', $transport->id) }}"
                                    class="px-4 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm md:text-base">
                                    Edit
                                </a>

                                <form action="{{ route('transports.destroy', $transport->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this transport request?');"
                                        class="px-4 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm md:text-base">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
