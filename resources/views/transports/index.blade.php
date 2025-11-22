@extends('layouts.app')

@section('title', 'Transport Requests')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">

    <h1 class="text-3xl font-bold mb-6 text-green-800">Transport Requests</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('transports.create') }}" class="mb-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 hover:shadow-lg transition transform hover:scale-105">
        Add New Transport
    </a>

    @if($transports->isEmpty())
        <p class="text-gray-600">No transport requests yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border text-left">Type</th>
                        <th class="p-3 border text-left">Passengers</th>
                        <th class="p-3 border text-left">Driver</th>
                        <th class="p-3 border text-left">Start</th>
                        <th class="p-3 border text-left">Destination</th>
                        <th class="p-3 border text-left">Distance (km)</th>
                        <th class="p-3 border text-left">Price</th>
                        <th class="p-3 border text-left">Departure</th>
                        <th class="p-3 border text-left">Arrival</th>
                        <th class="p-3 border text-left">Status</th>
                        <th class="p-3 border text-left w-48">Actions</th> <!-- عرض أكبر -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($transports as $transport)
                        <tr class="text-gray-700 hover:bg-gray-50 transition">
                            <td class="p-3 border">{{ $transport->transport_type }}</td>
                            <td class="p-3 border">{{ $transport->passengers }}</td>
                            <td class="p-3 border">{{ $transport->driver->name }}</td>
                            <td class="p-3 border">{{ $transport->start_point }}</td>
                            <td class="p-3 border">{{ $transport->destination }}</td>
                            <td class="p-3 border">{{ $transport->distance }}</td>
                            <td class="p-3 border">${{ $transport->price }}</td>
                            <td class="p-3 border">{{ $transport->departure_time }}</td>
                            <td class="p-3 border">{{ $transport->arrival_time ?? '-' }}</td>
                            <td class="p-3 border">
                                @if($transport->status === 'confirmed')
                                    <span class="px-2 py-1 bg-green-500 text-white rounded">Confirmed</span>
                                @elseif($transport->status === 'pending')
                                    <span class="px-2 py-1 bg-yellow-500 text-white rounded">Pending</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-400 text-white rounded">{{ ucfirst($transport->status) }}</span>
                                @endif
                            </td>
                            <td class="p-3 border space-x-2 flex justify-start w-48"> <!-- flex + w أكبر -->
                                <a href="{{ route('transports.edit', $transport->id) }}" class="px-4 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-center">Edit</a>

                                <form action="{{ route('transports.destroy', $transport->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to delete this transport request?');">Delete</button>
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
