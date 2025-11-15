@extends('layouts.app')

@section('title', 'Edit Transport Request')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-4xl font-bold mb-8 text-center text-blue-700">Edit Transport Request</h1>

    {{-- رسائل الخطأ --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- رسائل الفلاش --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('transports.update', $transport->id) }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg space-y-6" x-data="transportForm()">
        @csrf
        @method('PUT')

        {{-- Transport Type & Passengers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Transport Type</label>
                <select name="transport_type" class="w-full border p-3 rounded-lg" x-model="transport_type" @change="setRandomDriver()">
                    <option value="" disabled>Select Transport</option>
                    <option value="Car">Car (Max 4 passengers)</option>
                    <option value="Bus">Bus (Max 20 passengers)</option>
                    <option value="Large Bus">Large Bus (Max 50 passengers)</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 font-semibold">Passengers</label>
                <input type="number" name="passengers" class="w-full border p-3 rounded-lg" x-model.number="passengers" :max="maxPassengers" min="1">
                <p class="text-gray-500 mt-1 text-sm" x-text="'Maximum passengers: ' + maxPassengers"></p>
            </div>
        </div>

        {{-- Driver --}}
        <div>
            <label class="block mb-2 font-semibold">Driver (Assigned automatically)</label>
            <input type="text" class="w-full border p-3 rounded-lg bg-gray-100" x-model="driverName" readonly>
            <input type="hidden" name="driver_id" :value="driverId">
        </div>

        {{-- Start, Destination & Distance --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Start Point</label>
                <input type="text" name="start_point" class="w-full border p-3 rounded-lg" x-model="start_point">
            </div>
            <div>
                <label class="block mb-2 font-semibold">Destination</label>
                <input type="text" name="destination" class="w-full border p-3 rounded-lg" x-model="destination">
            </div>
            <div>
                <label class="block mb-2 font-semibold">Distance (km)</label>
                <input type="number" step="0.1" name="distance" class="w-full border p-3 rounded-lg" x-model.number="distance">
            </div>
        </div>

        {{-- Price --}}
        <div>
            <label class="block mb-2 font-semibold">Price ($)</label>
            <input type="number" name="price" class="w-full border p-3 rounded-lg bg-gray-100" :value="calculatedPrice" readonly>
            <p class="text-gray-500 text-sm mt-1">Distance × Passengers × Rate ($2/km)</p>
        </div>

        {{-- Departure & Arrival --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Departure Time</label>
                <input type="datetime-local" name="departure_time" class="w-full border p-3 rounded-lg" x-model="departure_time">
            </div>
            <div>
                <label class="block mb-2 font-semibold">Arrival Time</label>
                <input type="datetime-local" name="arrival_time" class="w-full border p-3 rounded-lg" x-model="arrival_time">
            </div>
        </div>

        {{-- Status & Notes --}}
        <div>
            <label class="block mb-2 font-semibold">Status</label>
            <select name="status" class="w-full border p-3 rounded-lg" x-model="status">
                <option value="Pending">Pending</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 font-semibold">Notes</label>
            <textarea name="notes" class="w-full border p-3 rounded-lg" x-model="notes"></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                Update Transport
            </button>
        </div>
    </form>

    {{-- Delete Button --}}
    <form action="{{ route('transports.destroy', $transport->id) }}" method="POST" class="mt-4 text-center" onsubmit="return confirm('Are you sure you want to delete this transport request?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete Transport</button>
    </form>

    <a href="{{ route('transports.index') }}" class="inline-block mt-6 text-blue-600 hover:underline">← Back to Transport Requests</a>
</div>

{{-- Alpine.js --}}
<script>
function transportForm() {
    return {
        transport_type: @json($transport->transport_type),
        passengers: @json($transport->passengers),
        driverId: @json($transport->driver_id),
        driverName: @json($transport->driver_name),
        start_point: @json($transport->start_point),
        destination: @json($transport->destination),
        distance: @json($transport->distance),
        departure_time: @json($transport->departure_time),
        arrival_time: @json($transport->arrival_time),
        status: @json($transport->status),
        notes: @json($transport->notes),
        rate: 2,
        randomDrivers: @json($randomDrivers ?? []),

        get calculatedPrice() {
            return (this.distance * this.passengers * this.rate).toFixed(2);
        },

        get maxPassengers() {
            switch(this.transport_type) {
                case 'Car': return 4;
                case 'Bus': return 20;
                case 'Large Bus': return 50;
                default: return 100;
            }
        },

        setRandomDriver() {
            if(this.transport_type && this.randomDrivers[this.transport_type]) {
                this.driverId = this.randomDrivers[this.transport_type]?.id ?? '';
                this.driverName = this.randomDrivers[this.transport_type]?.name ?? '';
            }
        }
    }
}
</script>
@endsection
