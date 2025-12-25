@extends('layouts.app')

@section('title', 'Add New Supply')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Add New Supply</h1>
                <p class="mt-1 text-gray-500 text-sm">Add new items to your inventory.</p>
            </div>
            <a href="{{ route('admin.supplies.index') }}" class="text-gray-500 hover:text-blue-600 transition flex items-center gap-1 text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancel
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <ul class="list-disc pl-5 space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.supplies.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 space-y-8"
              x-data="{ imagePreview: null }">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Supply Image</label>

                    <div class="relative group h-64">
                        <div class="w-full h-full rounded-2xl border-2 border-dashed border-gray-300 overflow-hidden flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition cursor-pointer relative">

                            <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover">

                            <div x-show="!imagePreview" class="text-center text-gray-400 p-4">
                                <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-sm">Click to upload</span>
                            </div>

                            <div x-show="imagePreview" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                <span class="text-white font-bold text-sm">Change Image</span>
                            </div>

                            <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Supply Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Organic Chicken Feed"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all" required>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price (JD)</label>
                            <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" placeholder="0.00"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                            <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="1"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" placeholder="Enter product details..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all resize-none">{{ old('description') }}</textarea>
                    </div>

                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-blue-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create Supply
                </button>
            </div>

        </form>
    </div>
@endsection
