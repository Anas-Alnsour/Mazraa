@extends('layouts.app')

@section('title', 'Manage Supplies')

@section('content')
    <div class="max-w-6xl mx-auto py-10 px-6 space-y-8">

        <h1 class="text-4xl font-extrabold text-green-800 text-center mb-8">Available Supplies</h1>
        <div class="flex space-x-4 mt-4">
            <a href="{{ route('admin.supplies.create') }}" style="width:10em ; height: 3em"
                class="px-4 py-3 bg-blue-600 text-white rounded-xl text-center hover:bg-blue-700 shadow-md transition-all duration-300 transform hover:scale-105">
                Add New
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-4 rounded-lg shadow-md text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($supply as $info)
                <div
                    class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-2xl transition-shadow duration-300">
                    <div class="mb-4">
                        <h2 class="text-2xl font-bold text-green-800 mb-2">{{ $info->name }}</h2>
                        <p class="text-gray-600 mb-2">{{ $info->description }}</p>
                        <p class="text-green-700 font-extrabold text-lg mb-2">${{ $info->price }}</p>
                        <p class="text-gray-500 mb-2">Stock: {{ $info->stock }}</p>
                    </div>

                    <div class="flex space-x-4 mt-4">
                        <a href="{{ route('admin.supplies.edit', $info->id) }}" style="width:90px"
                            class="px-4 py-2 bg-green-600 text-white rounded-xl text-center hover:bg-green-700 shadow-md transition-all duration-300 transform hover:scale-105">
                            Edit
                        </a>
                        <form action="{{ route('admin.supplies.destroy', $info->id) }}" method="POST" style="width:120px">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-xl text-center hover:bg-red-700 shadow-md transition-all duration-300 transform hover:scale-105">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
