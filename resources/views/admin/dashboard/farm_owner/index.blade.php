<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-[#1d5c42] leading-tight tracking-tighter">
            {{ __('Partner Overview') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f9f8f4] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 mb-8 flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h3 class="text-2xl font-black text-gray-900">Welcome back, {{ auth()->user()->name }}! 🚜</h3>
                    <p class="text-gray-500 font-bold mt-2 text-sm">Here is what's happening with your properties today.</p>
                </div>
                <a href="{{ route('farm_owner.farms.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-[#183126] hover:bg-[#10231b] text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg transition-transform active:scale-95">
                    Manage My Farms
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 flex items-center gap-6">
                    <div class="bg-green-50 p-4 rounded-2xl text-[#1d5c42]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Properties</p>
                        <p class="text-4xl font-black text-gray-900">{{ $totalFarms }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 flex items-center gap-6">
                    <div class="bg-[#fdf4e7] p-4 rounded-2xl text-[#b46146]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Bookings</p>
                        <p class="text-4xl font-black text-gray-900">{{ $totalBookings }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 flex items-center gap-6">
                    <div class="bg-amber-50 p-4 rounded-2xl text-amber-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pending Requests</p>
                        <p class="text-4xl font-black text-gray-900">{{ $pendingBookings }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
