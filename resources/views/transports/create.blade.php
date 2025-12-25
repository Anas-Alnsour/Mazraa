@extends('layouts.app')

@section('title', 'Add Transport Request')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">New Transport Request</h1>
                <p class="mt-1 text-gray-500 text-sm">Create a new schedule for transportation logistics.</p>
            </div>
            <a href="{{ route('transports.index') }}" class="text-gray-500 hover:text-green-600 transition flex items-center gap-1 text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancel
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc pl-5 space-y-1 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('transports.store') }}" method="POST"
              class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 space-y-8"
              x-data="transportForm({
                  transport_type: '{{ old('transport_type') }}',
                  passengers: {{ old('passengers', 1) }},
                  driverId: '{{ old('driver_id') }}',
                  farmId: '{{ old('farm_id') }}',
                  distance: {{ old('distance', 0) }},
                  arrival_time: '{{ old('Farm_Arrival_Time') }}',
                  departure_time: '{{ old('Farm_Departure_Time') }}',
                  notes: '{{ old('notes') }}'
              })">

            @csrf

            {{-- Hidden Input for Status --}}
            <input type="hidden" name="status" value="Pending">

            {{-- Section 1: Vehicle Details --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">1</span>
                    Vehicle Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Transport Type</label>
                        <div class="relative">
                            <select name="transport_type" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all appearance-none cursor-pointer" x-model="transport_type">
                                <option value="" disabled>Select Type</option>
                                <option value="Car">Car (Max 4)</option>
                                <option value="Bus">Bus (Max 20)</option>
                                <option value="Large Bus">Large Bus (Max 50)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Passengers <span class="text-xs font-normal text-gray-400" x-text="'(Max: ' + maxPassengers + ')'"></span>
                        </label>
                        <input type="number" name="passengers" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all"
                               x-model.number="passengers" :max="maxPassengers" min="1">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Section 2: Route & Logistics --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-sm">2</span>
                    Route & Logistics
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assign Driver</label>
                        <select name="driver_id" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all appearance-none cursor-pointer" x-model="driverId">
                            <option value="" disabled>Select Driver</option>
                            @foreach($Drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 top-8 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Destination Farm</label>
                        <select name="farm_id"
                                class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all appearance-none cursor-pointer"
                                x-model="farmId">
                            <option value="">Select Farm</option>
                            @foreach($farms as $farm)
                                <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 top-8 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start & Return Point</label>
                        <input type="text" name="start_and_return_point" value="{{ old('start_and_return_point') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all"
                               placeholder="e.g. Amman 7th Circle">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Distance (km)</label>
                        <input type="number" step="0.1" name="distance" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all"
                               x-model.number="distance" placeholder="0.0">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Section 3: Schedule & Pricing --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-sm">3</span>
                    Schedule & Pricing
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Arrival Time (at Farm)</label>
                        <input type="datetime-local" name="Farm_Arrival_Time" id="Farm_Arrival_Time"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 outline-none transition-all"
                               x-model="arrival_time">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Departure Time (from Farm)</label>
                        <input type="datetime-local" name="Farm_Departure_Time" id="Farm_Departure_Time"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 outline-none transition-all"
                               x-model="departure_time">
                    </div>
                </div>

                {{-- Price Display --}}
                <div class="bg-gray-900 rounded-xl p-5 text-white flex justify-between items-center shadow-lg">
                    <div>
                        <label class="block text-gray-400 text-xs uppercase tracking-wider font-bold mb-1">Estimated Price</label>
                        {{-- تم تعديل النص ليعكس طريقة الحساب الجديدة --}}
                        <p class="text-xs text-gray-500">Calculated based on Vehicle Type & Distance</p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-extrabold" x-text="calculatedPrice"></span>
                        <span class="text-lg font-bold text-green-400">JD</span>
                        <input type="hidden" name="price" :value="calculatedPrice">
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
                <textarea name="notes" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-gray-400 focus:ring-4 focus:ring-gray-100 outline-none transition-all h-24 resize-none"
                          x-model="notes" placeholder="Any specific requirements..."></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg hover:shadow-green-200 transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Submit Request
                </button>
            </div>

        </form>
    </div>

    {{-- JavaScript Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const arrivalTimeInput = document.getElementById('Farm_Arrival_Time');
            const departureTimeInput = document.getElementById('Farm_Departure_Time');
            const currentDateTime = new Date().toISOString().slice(0, 16);

            if (arrivalTimeInput) arrivalTimeInput.setAttribute('min', currentDateTime);
            if (departureTimeInput) departureTimeInput.setAttribute('min', currentDateTime);
        });

        function transportForm(initialData) {
            return {
                transport_type: initialData.transport_type || '',
                passengers: initialData.passengers || 1,
                driverId: initialData.driverId || '',
                farmId: initialData.farmId || '',
                distance: initialData.distance || 0,
                arrival_time: initialData.arrival_time || '',
                departure_time: initialData.departure_time || '',
                notes: initialData.notes || '',

                // 1. دالة تحديد سعر الكيلومتر حسب نوع المركبة
                get ratePerKm() {
                    switch (this.transport_type) {
                        case 'Car':
                            return 0.40; // 40 قرش للكيلو
                        case 'Bus':
                            return 0.70; // 70 قرش للكيلو
                        case 'Large Bus':
                            return 1.20; // 1.20 دينار للكيلو
                        default:
                            return 0.40; // الافتراضي
                    }
                },

                // 2. معادلة السعر الجديدة (المسافة × سعر الكيلو للمركبة)
                get calculatedPrice() {
                    let price = this.distance * this.ratePerKm;
                    return price > 0 ? price.toFixed(2) : "0.00";
                },

                get maxPassengers() {
                    switch (this.transport_type) {
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