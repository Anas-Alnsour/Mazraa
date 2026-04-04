<x-owner-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-[#020617] tracking-tight">My Properties</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your farms, update details, and track approval status.</p>
            </div>
            <a href="{{ route('owner.farms.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#c2a265] hover:bg-[#b09053] text-[#020617] text-sm font-bold rounded-xl transition-all shadow-lg shadow-[#c2a265]/20 hover:shadow-xl hover:-translate-y-0.5 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                List New Farm
            </a>
        </div>
    </x-slot>

    <!-- Success Message (Flash Session) -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3 shadow-sm animate-fade-in-up">
        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h3 class="text-sm font-bold text-green-800">Success</h3>
            <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <div class="pb-10">
        @if(isset($farms) && $farms->count() > 0)
            <!-- Premium Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($farms as $farm)
                <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">

                    <!-- Farm Image Hero -->
                    <div class="relative h-56 w-full bg-gray-200 overflow-hidden">
                        @if($farm->images && $farm->images->count() > 0)
                            <img src="{{ Storage::url($farm->images->first()->image_path) }}" alt="{{ $farm->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif

                        <!-- Status Badge overlay -->
                        <div class="absolute top-4 right-4">
                            @if($farm->is_approved === 1)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-green-500/90 text-white backdrop-blur-md shadow-lg border border-green-400/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    Approved
                                </span>
                            @elseif($farm->is_approved === 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-500/90 text-white backdrop-blur-md shadow-lg border border-yellow-400/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Pending Review
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-500/90 text-white backdrop-blur-md shadow-lg border border-red-400/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Rejected
                                </span>
                            @endif
                        </div>

                        <!-- Location overlay -->
                        <div class="absolute bottom-4 left-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#020617]/70 text-white backdrop-blur-md">
                                <svg class="w-3.5 h-3.5 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $farm->city ?? 'Unknown' }}, {{ $farm->region ?? 'Region' }}
                            </span>
                        </div>
                    </div>

                    <!-- Farm Details -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h2 class="text-xl font-bold text-[#020617] line-clamp-1" title="{{ $farm->name }}">{{ $farm->name }}</h2>
                            <div class="text-right flex-shrink-0">
                                <span class="block text-lg font-extrabold text-[#1d5c42]">{{ number_format($farm->price_per_night, 2) }} <span class="text-xs text-gray-500 font-medium">JOD/nt</span></span>
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-1">
                            {{ Str::limit($farm->description, 100) }}
                        </p>

                        <!-- Quick Stats row -->
                        <div class="flex items-center gap-4 text-xs font-semibold text-gray-500 py-3 border-t border-b border-gray-100 mb-4">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                {{ $farm->capacity ?? 0 }} Guests
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                {{ $farm->rooms ?? 0 }} Rooms
                            </div>
                        </div>

                        <!-- Owner Controls -->
                        <div class="grid grid-cols-2 gap-3 mt-auto">
                            <a href="{{ route('owner.farms.edit', $farm->id) }}" class="flex items-center justify-center gap-2 py-2.5 px-4 bg-gray-50 hover:bg-gray-100 text-[#020617] text-sm font-bold rounded-xl transition-colors border border-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>

                            <!-- Delete Form (Requires alpine.js for modal or just standard confirm) -->
                            <form action="{{ route('owner.farms.destroy', $farm->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this farm? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-bold rounded-xl transition-colors border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="mt-10">
                @if(method_exists($farms, 'links'))
                    {{ $farms->links() }}
                @endif
            </div>

        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 px-4 text-center bg-white rounded-3xl border border-dashed border-gray-300 shadow-sm">
                <div class="w-24 h-24 bg-[#c2a265]/10 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-[#020617] mb-2">No Properties Listed Yet</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-8">You haven't added any farms to your portfolio yet. Create your first listing to start accepting bookings and earning revenue.</p>

                <a href="{{ route('owner.farms.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#1d5c42] hover:bg-[#154531] text-white text-base font-bold rounded-xl transition-all shadow-lg shadow-[#1d5c42]/20 hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Your First Farm
                </a>
            </div>
        @endif
    </div>
</x-owner-layout>
