@extends('layouts.app')

@section('title', 'Order Supplies')

@section('content')
    <div class="max-w-6xl mx-auto py-10 px-6 space-y-8">

        <h1 class="text-4xl font-extrabold text-green-800 text-center mb-16">Available Supplies</h1>

        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-4 rounded-lg shadow-md text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- شبكة العرض -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">
            @foreach ($supplies as $info)
                <div
                    class="bg-gray-50 rounded-2xl shadow-md hover:shadow-xl 
            transition-transform duration-300 overflow-hidden min-h-[355px] max-h-[360px]
            transform hover:scale-105">

                    <!-- صورة المنتج -->
                    <div class="relative">
                        <img src="{{ $info->image ? Storage::url($info->image) : 'https://via.placeholder.com/800x400' }}"
                            alt="{{ $info->name }}"
                            class="w-full h-full rounded-xl object-cover shadow-md hover:shadow-xl transition-all transform hover:scale-105 duration-300 min-h-[360px] max-h-[360px]">

                        <!-- أزرار تعديل وحذف -->
                        <div class="absolute top-3 right-3 flex space-x-2 z-20 ">
                            <a href="{{ route('supplies.show', $info->id) }}"
                                class="mt-auto px-4 py-2 bg-green-600 text-white rounded-xl text-center hover:bg-green-700 shadow-md transition-all duration-300 transform hover:scale-105">
                                Order
                            </a>
                        </div>

                        <!-- Overlay عند المرور -->
                        <div
                            class="absolute inset-0 rounded-xl bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <div class="p-4 text-white w-full">
                                <h3 class="text-lg font-bold">{{ $info->name }}</h3>
                                <p class="text-sm mt-1">{{ \Illuminate\Support\Str::limit($info->description, 100) }}</p>
                                <p class="text-gray-200 text-sm mt-1">Stock: {{ $info->stock }}</p>
                                <p class="mt-2 font-extrabold text-green-200">${{ $info->price }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل أسفل الصورة -->
                    {{-- <div class="p-4 flex flex-col items-center text-center">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $info->name }}</h2>
                    <p class="text-gray-600 text-sm mt-2">{{ \Illuminate\Support\Str::limit($info->description, 60) }}</p>
                    <p class="text-green-700 font-bold mt-2">${{ $info->price }}</p>
                    <p class="text-gray-500 text-xs mt-1">Stock: {{ $info->stock }}</p>
                     <div class="flex space-x-4 mt-2">
                        <a href="{{ route('supplies.show', $info->id) }}"
                   class="mt-auto px-4 py-2 bg-green-600 text-white rounded-xl text-center hover:bg-green-700 shadow-md transition-all duration-300 transform hover:scale-105">
                    Order
                    </a>

                    </div>
                </div> --}}

                </div>
            @endforeach
        </div>
    </div>
@endsection
