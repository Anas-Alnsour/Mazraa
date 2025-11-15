@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="container mx-auto py-12 px-4 md:px-0">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-6 text-center">Edit Order</h1>

        <h2 class="text-xl font-semibold text-gray-700 mb-4 text-center">
            {{ $order->supply->name }}
        </h2>

        {{-- عرض رسائل الخطأ --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- رسائل الفلاش --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-6 text-center">
                {{ session('error') }}
            </div>
        @endif

        {{-- تعديل الكمية --}}
        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="mb-6">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" value="{{ old('quantity', $order->quantity) }}"
                    min="1" max="{{ $order->supply->stock + $order->quantity }}"
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-400 focus:outline-none">
                <p class="text-gray-500 text-sm mt-1">Max available: {{ $order->supply->stock + $order->quantity }}</p>
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200">
                Update Order
            </button>
        </form>

        {{-- إلغاء الطلب --}}
        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');" class="mb-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors duration-200">
                Cancel Order
            </button>
        </form>

        <a href="{{ route('orders.my_orders') }}" class="block mt-4 text-center text-blue-600 hover:underline font-medium">
            ← Back to My Orders
        </a>
    </div>
</div>
@endsection
