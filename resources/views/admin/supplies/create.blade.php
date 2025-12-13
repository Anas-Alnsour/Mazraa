@extends('layouts.app')

@section('title', 'New Supply')

@section('content')
    <div class="container mx-auto py-10">
        <h1 class="text-4xl font-bold mb-8 text-center text-blue-600">Add Supplies Request</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.supplies.store') }}" method="POST" class="bg-white p-6 rounded-2xl shadow-lg space-y-6 "
            style="margin-bottom:5em ;margin-left:10em ;margin-right:10em ">

            @csrf

            {{-- Name & Quantity & Price --}}

            {{-- Name --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block mb-2 font-semibold">Supply Name</label>
                    <input type="text" name="name" class="w-full border p-3 rounded-xl" placeholder="Supply Name"
                        value="{{ old('name') }}">
                </div>
                {{-- Quantity --}}
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity') }}"
                        placeholder=" Quantitye"
                        class="w-full border  bg-gray-50 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                {{-- Price --}}
                <div>
                    <label class="block mb-2 font-semibold">Price ($)</label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="Price" min="0"
                        step="any" class="w-full border p-3 rounded-xl bg-gray-50">

                </div>

            </div>


            {{-- description --}}

            <div>
                <label class="block mb-2 font-semibold">description</label>
                <textarea name="description" class="w-full border p-3 rounded-lg" 
                    placeholder="Extra description">{{ old('description') }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold  transition">
                    Create Suplly
                </button>
            </div>

        </form>
    </div>

@endsection
