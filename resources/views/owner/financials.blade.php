<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 tracking-tight">
            {{ __('Financial Overview') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-green-50 rounded-[2rem] flex items-center justify-center mb-6 border-8 border-green-100 shadow-inner">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tight">Financial Reports Coming Soon</h3>
            <p class="text-sm text-gray-400 max-w-md mx-auto font-bold">This section will contain detailed revenue tracking, invoices, and commission reports. It will be built in the next phases.</p>
        </div>
    </div>
</x-dashboard-layout>
