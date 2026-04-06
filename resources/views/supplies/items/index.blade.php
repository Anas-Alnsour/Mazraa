@extends('layouts.supply')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Inventory Management</h1>
            <p class="text-sm font-bold text-emerald-600 tracking-widest uppercase mt-1">Manage Products, Stock, and Pricing</p>
        </div>
        <a href="{{ route('supplies.items.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 rounded-r-2xl shadow-sm font-bold mb-8 animate-fade-in flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        @if($supplies->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Product Info</th>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</th> {{-- 💡 Added Category --}}
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Stock</th>
                            <th class="px-8 py-5 border-b-2 border-gray-100 bg-gray-50 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($supplies as $item)
                            <tr class="hover:bg-emerald-50/30 transition-colors duration-200">
                                <td class="px-8 py-6 bg-transparent">
                                    <div class="flex items-center gap-5">
                                        <div class="h-16 w-16 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 flex items-center justify-center shadow-sm">
                                            @if($item->image)
                                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                                            @else
                                                <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-gray-900 font-black text-base">{{ $item->name }}</p>
                                            <p class="text-[11px] font-bold text-gray-400 mt-1 line-clamp-1 max-w-xs uppercase tracking-widest">{{ $item->description ?: 'No description provided' }}</p>
                                        </div>
                                    </div>
                                </td>
                                {{-- 💡 Added Category Display --}}
                                <td class="px-8 py-6 bg-transparent text-left">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 bg-transparent text-right">
                                    <p class="text-lg font-black text-emerald-600">{{ number_format($item->price, 2) }} <span class="text-[10px] text-gray-400 uppercase tracking-widest">JOD</span></p>
                                </td>
                                <td class="px-8 py-6 bg-transparent text-center">
                                    @if($item->stock > 10)
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> {{ $item->stock }} in stock
                                        </span>
                                    @elseif($item->stock > 0)
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Low ({{ $item->stock }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Out of Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 bg-transparent text-right">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('supplies.items.edit', $item->id) }}" class="inline-flex items-center justify-center px-4 py-2 border-2 border-gray-100 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 bg-white hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-100 transition-all transform active:scale-95 shadow-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('supplies.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border-2 border-gray-100 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 bg-white hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all transform active:scale-95 shadow-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($supplies->hasPages())
                <div class="px-8 py-5 border-t border-gray-100 bg-gray-50">
                    {{ $supplies->links() }}
                </div>
            @endif
        @else
            <div class="px-8 py-16 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4 border border-gray-100 shadow-inner">
                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-1">No products in inventory</h3>
                <p class="text-sm font-bold text-gray-400 mb-6">You haven't added any supply products yet. Get started by adding your first item.</p>
                <a href="{{ route('supplies.items.create') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-8 rounded-2xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 uppercase tracking-widest text-xs">
                    Add First Product
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
