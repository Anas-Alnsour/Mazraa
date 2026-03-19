<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Supply Deliveries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Deliveries -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-600">Total Assignments</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $totalDeliveries }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Deliveries -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-600">To Be Delivered</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $pendingDeliveries }}</p>
                        </div>
                    </div>
                </div>

                <!-- Completed Deliveries -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $completedDeliveries }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deliveries Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">My Delivery Assignments</h3>

                    @if($myOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Order ID</th>
                                        <th scope="col" class="px-6 py-3">Customer</th>
                                        <th scope="col" class="px-6 py-3">Destination (Farm)</th>
                                        <th scope="col" class="px-6 py-3">Items</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($myOrders as $order)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $order->user->name ?? 'N/A' }}
                                                <br>
                                                <span class="text-xs text-gray-500">{{ $order->user->phone ?? '' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $order->farmBooking->farm->name ?? 'Direct Delivery' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $order->supply->name ?? 'N/A' }} (x{{ $order->quantity }})
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($order->status == 'completed' || $order->status == 'delivered')
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Delivered</span>
                                                @elseif($order->status == 'out_for_delivery')
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">En Route</span>
                                                @else
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-400">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                                <!-- Add route for marking delivered later -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">You have no delivery assignments yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
