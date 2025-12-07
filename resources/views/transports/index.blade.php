@extends('layouts.app')

@section('title', 'Transport Requests')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4">

        <h1 class="text-3xl font-bold mb-6 text-green-800">Transport Requests</h1>

        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-4 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('transports.create') }}"
            class="mb-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 hover:shadow-lg transition transform hover:scale-105">
            Add New Transport
        </a>

        @if ($transports->isEmpty())
            <p class="text-gray-600">No transport requests yet.</p>
        @else
            <div class="overflow-x-auto">
                <table style="margin-bottom: 5em" class="min-w-full border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                            <th class="p-4 text-left font-semibold">Type</th>
                            <th class="p-4 text-left font-semibold">Passengers</th>
                            <th class="p-4 text-left font-semibold">Driver</th>
                            <th class="p-4 text-left font-semibold">Start</th>
                            <th class="p-4 text-left font-semibold">Destination</th>
                            <th class="p-4 text-left font-semibold">Distance (km)</th>
                            <th class="p-4 text-left font-semibold">Price</th>
                            <th class="p-4 text-left font-semibold">Departure</th>
                            <th class="p-4 text-left font-semibold">Arrival</th>
                            <th class="p-4 text-left font-semibold">Status</th>
                            <th class="p-4 text-left font-semibold w-48">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($transports as $transport)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="p-4">{{ $transport->transport_type }}</td>
                                <td class="p-4">{{ $transport->passengers }}</td>
                                <td class="p-4">{{ $transport->driver->name }}</td>
                                <td class="p-4">{{ $transport->start_point }}</td>
                                <td class="p-4">{{ $transport->destination }}</td>
                                <td class="p-4">{{ $transport->distance }}</td>
                                <td class="p-4 font-semibold text-green-700">${{ $transport->price }}</td>
                                <td class="p-4">{{ $transport->departure_time }}</td>
                                <td class="p-4">{{ $transport->arrival_time ?? '-' }}</td>

                                <td class="p-4">
                                    @if ($transport->status === 'confirmed')
                                        <span
                                            class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full font-medium">
                                            Confirmed
                                        </span>
                                    @elseif($transport->status === 'pending')
                                        <span
                                            class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-full font-medium">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded-full font-medium">
                                            {{ ucfirst($transport->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 flex gap-2">
                                    <a href="{{ route('transports.edit', $transport->id) }}"
                                        class="px-4 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('transports.destroy', $transport->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this transport request?');"
                                            class="px-4 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
