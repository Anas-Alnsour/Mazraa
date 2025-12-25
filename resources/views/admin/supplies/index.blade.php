@extends('layouts.app')

@section('title', 'Manage Supplies')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-6">

        {{-- الهيدر وزر الإضافة --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manage Supplies</h1>
                <p class="mt-1 text-gray-500 text-sm">Overview of your inventory items.</p>
            </div>

            <a href="{{ route('admin.supplies.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-0.5 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Supply
            </a>
        </div>

        {{-- رسالة النجاح --}}
        @if (session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- التحقق مما إذا كانت القائمة فارغة --}}
        @if ($supplies->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Inventory Empty</h3>
                <p class="text-gray-500 mb-6">No supplies available yet.</p>
            </div>
        @else
            {{-- شبكة عرض العناصر --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($supplies as $supply)
                    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden hover:-translate-y-1">

                        {{-- صورة المنتج --}}
                        <div class="relative h-56 overflow-hidden bg-gray-100">
                            <img src="{{ $supply->image ? Storage::url($supply->image) : 'https://via.placeholder.com/800x600' }}"
                                 alt="{{ $supply->name }}"
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                            {{-- حالة المخزون (Badges) --}}
                            <div class="absolute top-3 left-3">
                                @if($supply->stock == 0)
                                    <span class="px-3 py-1 bg-red-500/90 backdrop-blur text-white text-xs font-bold uppercase rounded-lg shadow-sm">
                                        Out of Stock
                                    </span>
                                @elseif($supply->stock < 10)
                                    <span class="px-3 py-1 bg-yellow-500/90 backdrop-blur text-white text-xs font-bold uppercase rounded-lg shadow-sm">
                                        Low: {{ $supply->stock }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-green-600/90 backdrop-blur text-white text-xs font-bold uppercase rounded-lg shadow-sm">
                                        Stock: {{ $supply->stock }}
                                    </span>
                                @endif
                            </div>

                            {{-- السعر --}}
                            <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur px-3 py-1 rounded-lg text-sm font-extrabold text-green-700 shadow-sm">
                                {{ $supply->price }} JD
                            </div>
                        </div>

                        {{-- تفاصيل المنتج --}}
                        <div class="p-6 flex flex-col flex-1">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1" title="{{ $supply->name }}">
                                    {{ $supply->name }}
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ \Illuminate\Support\Str::limit($supply->description, 90) }}
                                </p>
                            </div>

                            {{-- أزرار التحكم --}}
                            <div class="mt-auto pt-4 border-t border-gray-100 flex gap-3">
                                <a href="{{ route('admin.supplies.edit', $supply->id) }}"
                                   class="flex-1 py-2 bg-yellow-50 text-yellow-600 font-bold text-sm rounded-xl hover:bg-yellow-100 transition text-center border border-yellow-100 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>

                                <form action="{{ route('admin.supplies.destroy', $supply->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this supply?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 bg-red-50 text-red-600 font-bold text-sm rounded-xl hover:bg-red-100 transition border border-red-100 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- التصفح (Pagination) --}}
        @if ($supplies->hasPages())
            <div class="mt-12 flex justify-center pb-8">
                <div class="bg-white shadow-sm rounded-2xl px-4 py-2 border border-gray-100">
                    {{-- تأكد من وجود ملف pagination/green.blade.php أو احذفه لاستخدام الافتراضي --}}
                    {{ $supplies->withQueryString()->links('pagination.green') }}
                </div>
            </div>
        @endif

    </div>
@endsection
