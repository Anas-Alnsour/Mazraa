@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-3">Book Farm Shuttle</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Reliable transport from your location to the farm and back</p>
        </div>

        @if($errors->any())
            <div class="mb-10 bg-red-50 border-l-4 border-red-500 p-5 rounded-r-2xl shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="h-6 w-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <h4 class="font-black text-red-800 text-sm uppercase tracking-widest mb-1">Please fix the following errors:</h4>
                        <ul class="list-disc pl-5 space-y-1 text-sm font-bold text-red-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-12"
             x-data="transportForm({
                 transport_type: '{{ old('transport_type') }}',
                 passengers: {{ old('passengers', 1) }},
                 farmId: '{{ old('farm_id') }}',
                 distance: {{ old('distance', 15) }},
                 arrival_time: '{{ old('Farm_Arrival_Time') }}',
                 departure_time: '{{ old('Farm_Departure_Time') }}',
                 notes: '{{ old('notes') }}'
             })">

            <form action="{{ route('transports.store') }}" method="POST">
                @csrf

                <div class="mb-10">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        Vehicle & Passenger Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Transport Type</label>
                            <select name="transport_type" required x-model="transport_type"
                                class="w-full pl-5 pr-10 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors appearance-none cursor-pointer">
                                <option value="" disabled>Select vehicle...</option>
                                <option value="Car">Standard Sedan (Up to 4)</option>
                                <option value="Bus">Mini Bus (Up to 20)</option>
                                <option value="Large Bus">Large Bus (Up to 50)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 top-6 flex items-center px-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2 flex items-center gap-2">
                                Passengers
                                <span class="text-[9px] text-blue-500 bg-blue-50 px-2 py-0.5 rounded border border-blue-100" x-text="'Max: ' + maxPassengers"></span>
                            </label>
                            <input type="number" name="passengers" required x-model.number="passengers" :max="maxPassengers" min="1"
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors">
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Routing & Schedule
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Pickup/Return Address</label>
                            <input type="text" name="start_and_return_point" value="{{ old('start_and_return_point') }}" required placeholder="e.g., 7th Circle, Amman"
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors">
                        </div>

                        <div class="relative">
                            <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Destination Farm</label>
                            <select name="farm_id" required x-model="farmId"
                                class="w-full pl-5 pr-10 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors appearance-none cursor-pointer">
                                <option value="">Select a destination farm...</option>
                                @foreach($farms as $farm)
                                    <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 top-6 flex items-center px-4 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Requested Arrival at Farm</label>
                            <input type="datetime-local" name="Farm_Arrival_Time" id="Farm_Arrival_Time" required x-model="arrival_time"
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Return Trip (Optional)</label>
                            <input type="datetime-local" name="Farm_Departure_Time" id="Farm_Departure_Time" x-model="departure_time"
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors">
                            <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Leave blank for one-way trips</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Estimated Distance (KM)</label>
                        <input type="number" step="0.1" name="distance" required x-model.number="distance"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors">
                    </div>
                </div>

                <div class="mb-10">
                    <label class="block text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Special Requests (Optional)</label>
                    <textarea name="notes" rows="3" placeholder="e.g., Need child seats, extra luggage space..." x-model="notes"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 text-sm font-bold text-gray-700 transition-colors resize-none"></textarea>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-[2rem] p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500 rounded-full opacity-20 blur-3xl"></div>
                    <div class="relative z-10 w-full md:w-auto">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-2">
                            Estimated Price
                            <span class="text-[8px] bg-gray-800 text-gray-300 px-2 py-0.5 rounded">Auto Calculated</span>
                        </p>
                        <p class="text-4xl font-black text-white">
                            <span x-text="calculatedPrice"></span>
                            <span class="text-sm font-bold text-blue-400 uppercase tracking-widest">JOD</span>
                        </p>
                        <input type="hidden" name="price" :value="calculatedPrice">
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-500 text-white font-black py-4 px-10 rounded-2xl shadow-lg transition-all transform active:scale-95 uppercase tracking-widest text-xs flex items-center justify-center gap-3 relative z-10">
                        Submit Request
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const arrivalTimeInput = document.getElementById('Farm_Arrival_Time');
        const departureTimeInput = document.getElementById('Farm_Departure_Time');

        // منع اختيار تاريخ بالماضي
        const currentDateTime = new Date().toISOString().slice(0, 16);
        if (arrivalTimeInput) arrivalTimeInput.setAttribute('min', currentDateTime);
        if (departureTimeInput) departureTimeInput.setAttribute('min', currentDateTime);
    });

    function transportForm(initialData) {
        return {
            transport_type: initialData.transport_type || '',
            passengers: initialData.passengers || 1,
            farmId: initialData.farmId || '',
            distance: initialData.distance || 0,
            arrival_time: initialData.arrival_time || '',
            departure_time: initialData.departure_time || '',
            notes: initialData.notes || '',

            // دالة تحديد سعر الكيلومتر حسب نوع المركبة
            get ratePerKm() {
                switch (this.transport_type) {
                    case 'Car': return 0.40; // 40 قرش للكيلو
                    case 'Bus': return 0.70; // 70 قرش للكيلو
                    case 'Large Bus': return 1.20; // 1.20 دينار للكيلو
                    default: return 0.40; // الافتراضي
                }
            },

            // معادلة السعر الديناميكية (المسافة × سعر الكيلو للمركبة)
            get calculatedPrice() {
                let price = this.distance * this.ratePerKm;
                return price > 0 ? price.toFixed(2) : "0.00";
            },

            // الحد الأقصى للركاب بناءً على المركبة
            get maxPassengers() {
                switch (this.transport_type) {
                    case 'Car': return 4;
                    case 'Bus': return 20;
                    case 'Large Bus': return 50;
                    default: return 50;
                }
            }
        }
    }
</script>
@endsection
