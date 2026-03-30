@extends('layouts.admin')

@section('content')
@php
    // Direct DB queries to fetch exact financial streams based on your requested schema.
    // 'credit' transactions where user_id is the Admin ID.
    $adminId = auth()->id(); // Assuming the logged-in user viewing this is the super admin.

    $farmProfit = DB::table('financial_transactions')
        ->where('user_id', $adminId)
        ->where('transaction_type', 'credit')
        ->where('reference_type', 'farm_booking')
        ->sum('amount');

    $transportProfit = DB::table('financial_transactions')
        ->where('user_id', $adminId)
        ->where('transaction_type', 'credit')
        ->where('reference_type', 'transport')
        ->sum('amount');

    $supplyProfit = DB::table('financial_transactions')
        ->where('user_id', $adminId)
        ->where('transaction_type', 'credit')
        ->where('reference_type', 'supply_order')
        ->sum('amount');

    $totalCombinedProfit = $farmProfit + $transportProfit + $supplyProfit;
@endphp

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 border-b border-gray-200 pb-5">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">Super Admin Financial Dashboard</h1>
        <p class="mt-2 text-sm text-gray-500">Comprehensive overview of platform revenue streams and net profit.</p>
    </div>

    <!-- Financial Summary Cards Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        <!-- 1. Farm Bookings Profit -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-indigo-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <!-- Icon: Home/Farm -->
                        <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Farm Bookings (Commission)</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($farmProfit, 2) }} <span class="text-sm font-medium text-gray-500">JOD</span></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-900">View transactions</a>
                </div>
            </div>
        </div>

        <!-- 2. Transport Profit -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <!-- Icon: Truck -->
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Transport Profit (100% Admin)</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($transportProfit, 2) }} <span class="text-sm font-medium text-gray-500">JOD</span></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-900">View transactions</a>
                </div>
            </div>
        </div>

        <!-- 3. Supply Orders Profit -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <!-- Icon: Shopping Bag -->
                        <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Supply Orders (Commission)</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($supplyProfit, 2) }} <span class="text-sm font-medium text-gray-500">JOD</span></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-900">View transactions</a>
                </div>
            </div>
        </div>

        <!-- 4. Total Combined Profit -->
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <!-- Icon: Currency/Banknotes -->
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-900 truncate uppercase tracking-wider">Total Combined Profit</dt>
                            <dd class="flex items-baseline">
                                <div class="text-3xl font-bold text-green-600">{{ number_format($totalCombinedProfit, 2) }} <span class="text-lg font-medium text-green-800">JOD</span></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-green-800">Overall Platform Net Income</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
