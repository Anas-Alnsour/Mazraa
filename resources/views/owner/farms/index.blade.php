<x-owner-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-[#1d5c42] rounded-xl flex items-center justify-center text-white shadow-lg shadow-[#1d5c42]/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#020617] tracking-tight">My Properties</h1>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Manage your farms, update details, and check approval status.</p>
                </div>
            </div>

            <a href="{{ route('owner.farms.create') }}" class="inline-flex items-center justify-center gap-2 py-3 px-6 text-sm font-black tracking-widest uppercase rounded-xl text-white bg-[#1d5c42] hover:bg-[#154230] shadow-lg shadow-[#1d5c42]/20 transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                List New Farm
            </a>
        </div>
    </x-slot>

    <div class="pb-24 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 shadow-sm animate-fade-in-up flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            @if($farms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($farms as $farm)
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col">

                            <div class="relative h-56 w-full bg-gray-100 overflow-hidden">
                                {{-- 💡 FIX: Read `main_image` directly from the farm model --}}
                                @if($farm->main_image)
                                    <img src="{{ asset('storage/' . $farm->main_image) }}" alt="{{ $farm->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif

                                <div class="absolute top-4 right-4">
                                    @if($farm->is_approved === 1)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-green-500/90 text-white backdrop-blur-md shadow-sm border border-green-400/50 uppercase tracking-widest">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-amber-500/90 text-white backdrop-blur-md shadow-sm border border-amber-400/50 uppercase tracking-widest">
                                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-xl font-black text-gray-900 tracking-tight line-clamp-1">{{ $farm->name }}</h2>
                                </div>

                                <div class="flex items-center gap-1.5 text-sm font-bold text-gray-500 mb-4">
                                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span class="truncate">{{ $farm->location ?? $farm->governorate }}</span>
                                </div>

                                {{-- 💡 FIX: Using Shift Prices instead of price_per_night --}}
                                <div class="flex items-center gap-4 py-4 border-y border-gray-100 mt-auto mb-6">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Day Shift</p>
                                        <p class="text-sm font-black text-[#1d5c42]">{{ $farm->price_per_morning_shift }} <span class="text-[9px] text-gray-400">JOD</span></p>
                                    </div>
                                    <div class="w-px h-8 bg-gray-200"></div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Full Day</p>
                                        <p class="text-sm font-black text-[#c2a265]">{{ $farm->price_per_full_day }} <span class="text-[9px] text-gray-400">JOD</span></p>
                                    </div>
                                    <div class="ml-auto text-right">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Capacity</p>
                                        <p class="text-sm font-black text-gray-900">{{ $farm->capacity }} <span class="text-[9px] text-gray-400">PAX</span></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('owner.farms.edit', $farm->id) }}" class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-900 text-xs font-black uppercase tracking-widest rounded-xl transition-colors border border-gray-200">
                                        Edit Details
                                    </a>

                                    <form action="{{ route('owner.farms.destroy', $farm->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('Are you sure you want to delete this farm? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2.5 bg-white hover:bg-red-50 text-red-600 hover:text-red-700 text-xs font-black uppercase tracking-widest rounded-xl transition-colors border border-red-200 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($farms->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $farms->links('pagination.green') }}
                    </div>
                @endif

            @else
                <div class="bg-white rounded-3xl border-2 border-dashed border-gray-200 p-12 text-center flex flex-col items-center justify-center max-w-2xl mx-auto shadow-sm">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6 border border-emerald-100">
                        <svg class="w-10 h-10 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">No Properties Listed Yet</h3>
                    <p class="text-sm font-medium text-gray-500 max-w-md mx-auto mb-8">You haven't added any farms to your portfolio. Start by listing your first property to accept bookings and earn revenue.</p>
                    <a href="{{ route('owner.farms.create') }}" class="inline-flex items-center justify-center gap-2 py-4 px-8 text-sm font-black tracking-widest uppercase rounded-2xl text-white bg-[#1d5c42] hover:bg-[#154230] shadow-lg shadow-[#1d5c42]/20 transform hover:-translate-y-1 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        List Your First Farm
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-owner-layout>
