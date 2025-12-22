@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6 bg-white rounded-2xl shadow-lg">
    <h1 class="text-4xl font-extrabold text-green-800 mb-8 text-center">Shopping Cart</h1>

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

    @if($cartOrders->isEmpty())
        <p class="text-gray-600 text-center text-lg">Your cart is empty. <a href="{{ route('supplies.index') }}" class="text-blue-600 hover:underline">Go back to supplies</a></p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left border-b">Supply</th>
                        <th class="p-3 text-center border-b">Quantity</th>
                        <th class="p-3 text-center border-b">Price</th>
                        <th class="p-3 text-center border-b">Total</th>
                        <th class="p-3 text-center border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartOrders as $order)
                        <tr class="text-center hover:bg-gray-50 transition">
                            <td class="p-3 text-left border-b">{{ $order->supply->name }}</td>
                            <td class="p-3 border-b">
                                <form action="{{ route('cart.update', $order->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" min="1" max="{{ $order->supply->stock }}" value="{{ $order->quantity }}" class="w-16 px-2 py-1 border rounded text-center">
                                    <button type="submit" class="ml-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                                </form>
                            </td>
                            <td class="p-3 border-b">${{ $order->supply->price }}</td>
                            <td class="p-3 border-b">${{ $order->total_price }}</td>
                            <td class="p-3 border-b">
                                <form action="{{ route('cart.remove', $order->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this item from cart?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end items-center space-x-6">
            <div class="text-xl font-bold text-gray-800">Total: ${{ $total }}</div>

            <form action="{{ route('cart.place_order') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition transform hover:scale-105">
                    Place Order
                </button>
            </form>
        </div>
    @endif
</div>
@endsection