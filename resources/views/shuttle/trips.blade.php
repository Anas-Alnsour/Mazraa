<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Transport Trips') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Trips -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-600">Total Assignments</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $totalTrips }}</p>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Trips -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-600">Upcoming / Active</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $upcomingTrips }}</p>
                        </div>
                    </div>
                </div>

                <!-- Completed Trips -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-600">Completed Trips</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $completedTrips }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trips Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">My Assigned Trips</h3>

                    @if($myTrips->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Trip ID</th>
                                        <th scope="col" class="px-6 py-3">Customer</th>
                                        <th scope="col" class="px-6 py-3">Route</th>
                                        <th scope="col" class="px-6 py-3">Arrival Time</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($myTrips as $trip)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                #{{ $trip->id }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $trip->user->name ?? 'N/A' }}
                                                <br>
                                                <span class="text-xs text-gray-500">{{ $trip->user->phone ?? '' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="font-semibold">From:</span> {{ $trip->start_and_return_point }}<br>
                                                <span class="font-semibold">To:</span> {{ $trip->farm->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ \Carbon\Carbon::parse($trip->Farm_Arrival_Time)->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($trip->status == 'completed' || $trip->status == 'finished')
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Completed</span>
                                                @elseif($trip->status == 'in_progress')
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">In Progress</span>
                                                @else
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-400">{{ ucfirst(str_replace('_', ' ', $trip->status)) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">View Map</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">You have no transport assignments yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
