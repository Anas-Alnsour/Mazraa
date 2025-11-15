@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6 bg-white rounded-2xl shadow-lg">
    <h1 class="text-4xl font-extrabold text-green-800 mb-8 text-center">My Orders</h1>

    {{-- رسائل الفلاش --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 p-4 rounded mb-6 shadow-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 p-4 rounded mb-6 shadow-md">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <p class="text-gray-600 text-center text-lg">You have no orders.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left border-b">Supply</th>
                        <th class="p-3 text-center border-b">Quantity</th>
                        <th class="p-3 text-center border-b">Total Price</th>
                        <th class="p-3 text-center border-b">Status</th>
                        <th class="p-3 text-center border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPrice = 0; @endphp
                    @foreach($orders as $order)
                        @php $totalPrice += $order->total_price; @endphp
                        <tr class="text-center hover:bg-gray-50 transition">
                            <td class="p-3 text-left border-b">{{ $order->supply->name }}</td>
                            <td class="p-3 border-b">{{ $order->quantity }}</td>
                            <td class="p-3 border-b">${{ $order->total_price }}</td>
                            <td class="p-3 border-b">
                                @if($order->status === 'placed')
                                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm">Placed</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-sm">Pending</span>
                                @endif
                            </td>
                            <td class="p-3 border-b space-x-2">
                                @if($order->status !== 'placed')
                                    <a href="{{ route('orders.edit', $order->id) }}" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">Edit</a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">Cancel</button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-sm">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- المجموع --}}
        <div class="mt-6 flex justify-end items-center space-x-6">
            <div class="text-xl font-bold text-gray-800">Total: ${{ $totalPrice }}</div>

            {{-- زر Place All Orders --}}
            <form action="{{ route('orders.place_all') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition transform hover:scale-105">
                    Place All Orders
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
