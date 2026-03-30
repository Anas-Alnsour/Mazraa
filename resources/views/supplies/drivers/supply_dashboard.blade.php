@extends('layouts.driver')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Supply Dispatch Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage and deliver your assigned farm supply orders.</p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No active orders</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You currently have no assigned supply deliveries in your region.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col border border-gray-200 dark:border-gray-700">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order #{{ $order->id }}</h3>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ number_format($order->total_price, 2) }} JOD</span>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            <p><strong class="text-gray-900 dark:text-white">Farm Destination:</strong> {{ $order->farmBooking->farm->name ?? 'Farm' }}</p>
                            <p><strong class="text-gray-900 dark:text-white">Customer:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                            <p><strong class="text-gray-900 dark:text-white">Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-2">Ordered at: {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Visual Status Progress -->
                    <div class="p-4 bg-gray-50 dark:bg-gray-750 flex-grow">
                        <div class="relative">
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200 dark:bg-gray-600">
                                @php
                                    $progress = 0;
                                    if($order->status == 'pending') $progress = 25;
                                    if($order->status == 'waiting_driver') $progress = 50;
                                    if($order->status == 'in_way') $progress = 75;
                                    if($order->status == 'delivered') $progress = 100;
                                @endphp
                                <div style="width: {{ $progress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500 transition-all duration-500"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 font-medium">
                                <span class="{{ $progress >= 25 ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Preparing</span>
                                <span class="{{ $progress >= 50 ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Waiting Driver</span>
                                <span class="{{ $progress >= 75 ? 'text-indigo-600 dark:text-indigo-400' : '' }}">On the Way</span>
                                <span class="{{ $progress >= 100 ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Delivered</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <form action="{{ route('supply.driver.update_status', $order->id) }}" method="POST" class="flex space-x-2">
                            @csrf
                            @method('PATCH')

                            @if($order->status == 'waiting_driver')
                                <button name="status" value="in_way" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                                    Mark "On the Way"
                                </button>
                            @elseif($order->status == 'in_way')
                                <button name="status" value="delivered" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                                    Mark "Delivered"
                                </button>
                            @elseif($order->status == 'delivered')
                                <button type="button" disabled class="flex-1 bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">
                                    Delivery Complete
                                </button>
                            @else
                                <button type="button" disabled class="flex-1 bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">
                                    Waiting on Store
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
