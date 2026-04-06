@extends('layouts.supply')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Edit Product</h1>
            <p class="text-sm font-bold text-gray-400 tracking-widest uppercase mt-1">Update <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded-md font-mono">{{ $item->name }}</span></p>
        </div>
        <a href="{{ route('supplies.items.index') }}" class="group text-gray-500 hover:text-gray-900 font-bold flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Inventory
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-2xl mb-8 shadow-sm">
            <ul class="list-disc pl-5 font-bold text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-12">
        <form action="{{ route('supplies.items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">Product Image</label>

                <div class="flex flex-col sm:flex-row items-center gap-6">
                    @if($item->image)
                        <div class="h-32 w-32 rounded-[2rem] border-4 border-gray-50 overflow-hidden flex-shrink-0 shadow-md">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                        </div>
                    @endif

                    <div class="flex-1 w-full flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-3xl hover:border-emerald-400 hover:bg-emerald-50/30 transition-all bg-gray-50 group cursor-pointer" onclick="document.getElementById('image').click()">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 48 48" stroke="currentColor"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <span class="relative bg-transparent font-black text-emerald-600 uppercase tracking-widest text-[10px]">
                                    Upload New Image
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </span>
                            </div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Leave blank to keep current</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-gray-100">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">
                </div>

                {{-- 💡 Added Category Field --}}
                <div class="md:col-span-2">
                    <label for="category" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Category</label>
                    <select name="category" id="category" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">
                        @foreach(['Meat & BBQ', 'Charcoal & Firewood', 'Beverages & Snacks', 'Cleaning Supplies', 'Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $item->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6">{{ old('description', $item->description) }}</textarea>
                </div>

                <div>
                    <label for="price" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Unit Price</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $item->price) }}" required min="0"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-black rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 pl-6 pr-16 text-xl">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none text-gray-400 font-black text-[10px] uppercase tracking-widest">
                            JOD
                        </div>
                    </div>
                </div>

                <div>
                    <label for="stock" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">Available Stock</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $item->stock) }}" required min="0"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-black rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all py-4 px-6 text-xl">
                </div>
            </div>

            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('supplies.items.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-black py-4 px-10 rounded-2xl transition-all transform active:scale-95 tracking-widest uppercase text-sm w-full md:w-auto text-center border border-gray-200">
                    Cancel
                </a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 tracking-widest uppercase text-sm w-full md:w-auto">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
