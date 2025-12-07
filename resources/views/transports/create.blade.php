@extends('layouts.app')

@section('title', 'Add Transport Request')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-4xl font-bold mb-8 text-center text-green-700">Add Transport Request</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transports.store') }}" method="POST"
        class="bg-white p-8 rounded-lg shadow-lg space-y-6"
        x-data="transportForm({
            transport_type: '{{ old('transport_type') }}',
            passengers: {{ old('passengers', 1) }},
            driverId: '{{ old('driver_id') }}',
            start_point: '{{ old('start_point') }}',
            destination: '{{ old('destination') }}',
            distance: {{ old('distance', 0) }},
            departure_time: '{{ old('departure_time') }}',
            arrival_time: '{{ old('arrival_time') }}',
            status: '{{ old('status', 'Pending') }}',
            notes: '{{ old('notes') }}'
        })" style="margin-bottom:5em ;margin-left:5em ;margin-right:5em ">

        @csrf

        {{-- Transport Type & Passengers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Transport Type</label>
                <select name="transport_type" class="w-full border p-3 rounded-lg" x-model="transport_type">
                    <option value="" disabled>Select Transport</option>
                    <option value="Car">Car (Max 4 passengers)</option>
                    <option value="Bus">Bus (Max 20 passengers)</option>
                    <option value="Large Bus">Large Bus (Max 50 passengers)</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Passengers</label>
                <input type="number" name="passengers" class="w-full border p-3 rounded-lg"
                       x-model.number="passengers" :max="maxPassengers" min="1">
                <p class="text-gray-500 mt-1 text-sm" x-text="'Maximum passengers: ' + maxPassengers"></p>
            </div>
        </div>

        {{-- Driver Dropdown --}}
        <div>
            <label class="block mb-2 font-semibold">Driver</label>
            <select name="driver_id" class="w-full border p-3 rounded-lg" x-model="driverId">
                <option value="" disabled>Select Driver</option>
                @foreach($Drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Start & Destination & Distance --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Start Point</label>
                <input type="text" name="start_point" class="w-full border p-3 rounded-lg"
                       x-model="start_point" placeholder="Starting location">
            </div>

            <div>
                <label class="block mb-2 font-semibold">Destination</label>
                <input type="text" name="destination" class="w-full border p-3 rounded-lg"
                       x-model="destination" placeholder="Destination">
            </div>

            <div>
                <label class="block mb-2 font-semibold">Distance (km)</label>
                <input type="number" step="0.1" name="distance" class="w-full border p-3 rounded-lg"
                       x-model.number="distance">
            </div>
        </div>

        {{-- Price --}}
        <div>
            <label class="block mb-2 font-semibold">Price ($)</label>
            <input type="number" name="price" class="w-full border p-3 rounded-lg bg-gray-100"
                   :value="calculatedPrice" readonly>
            <p class="text-gray-500 text-sm mt-1">Distance × Passengers × Rate ($2/km)</p>
        </div>

        {{-- Departure & Arrival --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 font-semibold">Departure Time</label>
                <input type="datetime-local" name="departure_time" class="w-full border p-3 rounded-lg"
                       x-model="departure_time">
            </div>

            <div>
                <label class="block mb-2 font-semibold">Arrival Time</label>
                <input type="datetime-local" name="arrival_time" class="w-full border p-3 rounded-lg"
                       x-model="arrival_time">
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block mb-2 font-semibold">Status</label>
            <select name="status" class="w-full border p-3 rounded-lg" x-model="status">
                <option value="Pending">Pending</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block mb-2 font-semibold">Notes</label>
            <textarea name="notes" class="w-full border p-3 rounded-lg"
                      x-model="notes" placeholder="Extra notes"></textarea>
        </div>

        <div class="text-center">
            <button type="submit"
                class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                Create Transport
            </button>
        </div>

    </form>
</div>

<script>
function transportForm(oldValues) {
    return {
        transport_type: oldValues.transport_type || '',
        passengers: oldValues.passengers || 1,
        driverId: oldValues.driverId || '',
        start_point: oldValues.start_point || '',
        destination: oldValues.destination || '',
        distance: oldValues.distance || 0,
        departure_time: oldValues.departure_time || '',
        arrival_time: oldValues.arrival_time || '',
        status: oldValues.status || 'Pending',
        notes: oldValues.notes || '',
        rate: 2,

        get calculatedPrice() {
            return (this.distance/15 * this.passengers * this.rate).toFixed(2);
        },

        get maxPassengers() {
            switch(this.transport_type) {
                case 'Car': return 4;
                case 'Bus': return 20;
                case 'Large Bus': return 50;
                default: return 100;
            }
        }
    }
}
</script>
@endsection
