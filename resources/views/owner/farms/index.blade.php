<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 tracking-tight leading-tight">
                {{ __('My Farms Portfolio') }}
            </h2>
            <a href="{{ route('owner.farms.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-green-200 transition-all flex items-center gap-2 transform active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Farm
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-5 rounded-2xl flex items-start shadow-sm animate-fade-in">
                <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-green-700 font-black uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if($farms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($farms as $farm)
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl hover:shadow-gray-200/40 transition-all duration-500 group">

                        <div class="relative h-56 bg-gray-100 overflow-hidden">
                            @if($farm->main_image)
                                <img src="{{ asset('storage/' . $farm->main_image) }}" alt="{{ $farm->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">No Image</span>
                                </div>
                            @endif

                            <div class="absolute top-4 right-4">
                                @if($farm->status == 'pending')
                                    <span class="bg-amber-100/90 backdrop-blur-md text-amber-700 text-[10px] font-black px-4 py-2 rounded-full shadow-sm border border-amber-200 uppercase tracking-widest">
                                        Pending Review
                                    </span>
                                @else
                                    <span class="bg-green-100/90 backdrop-blur-md text-green-700 text-[10px] font-black px-4 py-2 rounded-full shadow-sm border border-green-200 uppercase tracking-widest">
                                        Live & Active
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-black text-gray-900 tracking-tighter truncate pr-4">{{ $farm->name }}</h3>
                                <div class="text-right">
                                    <span class="text-xl font-black text-green-600 tracking-tighter">JOD {{ number_format($farm->price_per_night, 0) }}</span>
                                    <p class="text-[8px] text-gray-400 font-black uppercase tracking-tighter -mt-1">Per Night</p>
                                </div>
                            </div>

                            <p class="text-xs font-bold text-gray-400 mb-6 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path></svg>
                                {{ Str::limit($farm->location, 35) }}
                            </p>

                            <div class="grid grid-cols-2 gap-6 mb-8 border-t border-gray-50 pt-6 mt-auto">
                                <div>
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Capacity</p>
                                    <p class="text-sm font-black text-gray-700 flex items-center">
                                        {{ $farm->capacity }} Guests
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Total Bookings</p>
                                    <p class="text-sm font-black text-gray-700">
                                        {{ $farm->bookings->count() }} Orders
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-2">
<a href="{{ route('owner.farms.edit', $farm->id) }}" class="flex-1 text-center py-3 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-600 transition-all shadow-md active:scale-95">
   Edit Farm
</a>
                                <form method="POST" action="{{ route('owner.farms.destroy', $farm->id) }}" onsubmit="return confirm('Permanent delete?');" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-white border border-red-100 text-red-500 hover:bg-red-50 font-black py-3 rounded-2xl transition-all text-[10px] uppercase tracking-widest shadow-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $farms->links() }}
            </div>

        @else
            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 py-24 px-6 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-green-50 rounded-[2rem] flex items-center justify-center mb-6 border-8 border-green-100 shadow-inner">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tight">Build your farm portfolio</h3>
                <p class="text-sm text-gray-400 max-w-sm mx-auto mb-10 font-bold">Add your first farm to start receiving bookings and generating revenue on Mazraa.</p>

                <a href="{{ route('owner.farms.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-black py-5 px-10 rounded-[1.5rem] shadow-xl shadow-green-200 transition-all hover:-translate-y-1 flex items-center gap-3 text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    List Your First Farm
                </a>
            </div>
        @endif

    </div>
</x-dashboard-layout>
