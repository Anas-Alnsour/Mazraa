@extends('layouts.supply')

@section('title', 'Global Catalog Manager')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Sleek Horizontal Scrollbar for Filters */
    .filter-scroll::-webkit-scrollbar { height: 4px; }
    .filter-scroll::-webkit-scrollbar-track { background: transparent; }
    .filter-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 4px; }
    .filter-scroll::-webkit-scrollbar-thumb:hover { background: #6366f1; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 mt-8 animate-god-in" x-data="{ addModalOpen: false, editMode: false, editUrl: '', editData: {} }">

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-6 shadow-sm text-sm font-bold fade-in-up">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl mb-6 shadow-sm text-sm font-bold fade-in-up">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl mb-6 shadow-sm text-sm font-bold fade-in-up">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🌟 Header --}}
    <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl overflow-hidden relative fade-in-up">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-[60px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-slate-950 rounded-2xl border border-slate-800 shadow-inner flex items-center justify-center text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m-9-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight">Global Catalog</h2>
                    <p class="text-xs font-black text-slate-500 mt-1 uppercase tracking-widest text-indigo-400/50">Manage System-Wide Products by Category</p>
                </div>
            </div>
            <div>
                <button @click="addModalOpen = true" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-[0_0_15px_rgba(79,70,229,0.3)] transition-all font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Deploy Product
                </button>
            </div>
        </div>
    </div>

    {{-- 🌟 Category Filter Bar --}}
    <div class="flex overflow-x-auto filter-scroll pb-2 gap-2 fade-in-up" style="animation-delay: 0.1s;">
        @php
            $categories = ['لحوم ومشاوي', 'خضار وفواكه', 'مقبلات وسلطات', 'أدوات ومعدات الشواء', 'تسالي وحلويات', 'مشروبات وثلج', 'مستلزمات السفرة والنظافة', 'ألعاب وترفيه'];
        @endphp

        <a href="{{ route('supplies.hq.catalog') }}"
           class="px-5 py-2.5 rounded-xl whitespace-nowrap text-sm font-black transition-all border shadow-sm {{ !request('category') ? 'bg-indigo-600 text-white border-indigo-500 shadow-[0_0_10px_rgba(79,70,229,0.3)]' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800' }}">
            الكل (All)
        </a>

        @foreach($categories as $cat)
            <a href="{{ route('supplies.hq.catalog', ['category' => $cat]) }}"
               class="px-5 py-2.5 rounded-xl whitespace-nowrap text-sm font-black transition-all border shadow-sm {{ request('category') == $cat ? 'bg-indigo-600 text-white border-indigo-500 shadow-[0_0_10px_rgba(79,70,229,0.3)]' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- 🌟 Catalog Grid --}}
    <div class="fade-in-up" style="animation-delay: 0.2s;">
        @if($globalSupplies->isEmpty())
            <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 backdrop-blur-2xl p-16 text-center shadow-2xl">
                <div class="w-20 h-20 bg-slate-950 rounded-3xl flex items-center justify-center border border-slate-800 mx-auto mb-6 shadow-inner text-slate-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m-9-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-xl font-black text-white tracking-tight">No Products Found</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-2">Deploy a product or select a different category.</p>
            </div>
        @else
            {{-- Products Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($globalSupplies as $supply)
                    <div class="bg-slate-900/60 rounded-[2rem] border border-slate-800 p-6 flex flex-col justify-between transition-all hover:-translate-y-1 hover:shadow-xl hover:border-indigo-500/30 group relative overflow-hidden backdrop-blur-xl">

                        {{-- Decorative glow --}}
                        <div class="absolute right-0 top-0 w-20 h-20 bg-indigo-500/5 rounded-bl-full pointer-events-none group-hover:bg-indigo-500/10 transition-colors"></div>

                        <div>
                            <div class="flex justify-between items-start mb-5">
                                <div class="w-16 h-16 bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden shrink-0 shadow-inner">
                                    @if($supply->image)
                                        <img src="{{ asset('storage/' . $supply->image) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-500">
                                    @else
                                        <div class="w-full h-full flex justify-center items-center text-[9px] text-slate-600 font-black uppercase">No Img</div>
                                    @endif
                                </div>
                                <div class="bg-slate-950 border border-slate-800 px-3 py-1.5 rounded-xl shadow-inner text-right">
                                    <span class="text-lg font-black text-emerald-400 tracking-tighter">{{ number_format($supply->price, 2) }}</span>
                                    <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest ml-0.5">JOD</span>
                                </div>
                            </div>

                            <h4 class="text-base font-black text-white truncate group-hover:text-indigo-400 transition-colors" title="{{ $supply->name }}">{{ $supply->name }}</h4>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1 mb-2">{{ $supply->category ?: 'Uncategorized' }}</p>
                            <p class="text-[10px] text-slate-500 mt-2 line-clamp-2 h-8 leading-relaxed">{{ $supply->description }}</p>
                        </div>

                        <div class="mt-6 pt-5 border-t border-slate-800/50 flex items-center justify-end gap-2 relative z-10">
                            <button @click="editData = {{ \Illuminate\Support\Js::from($supply) }}; editUrl = '{{ route('supplies.catalog.update', $supply->id) }}'; editMode = true" class="p-2 bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('supplies.catalog.destroy', $supply->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this global product?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2.5 bg-slate-950 border border-slate-800 hover:border-rose-500/50 hover:bg-rose-600/10 text-slate-400 hover:text-rose-400 rounded-xl transition-all active:scale-95 shadow-inner">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if(method_exists($globalSupplies, 'hasPages') && $globalSupplies->hasPages())
                <div class="mt-10 bg-slate-900/60 backdrop-blur-2xl rounded-[2rem] p-6 border border-slate-800 shadow-xl">
                    {{ $globalSupplies->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- 🌟 Create Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm" style="display: none;">
        <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-8 w-full max-w-lg shadow-2xl relative" @click.away="addModalOpen = false; document.getElementById('addForm').reset();">
            <h3 class="text-2xl font-black text-white mb-6">Deploy Global Product</h3>
            <form id="addForm" action="{{ route('supplies.catalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Product Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Category</label>
                    <select name="category" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Price (JOD)</label>
                        <input type="number" step="0.01" name="price" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Image</label>
                        <input type="file" name="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-indigo-400 hover:file:bg-slate-700 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                    <textarea name="description" rows="3" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="addModalOpen = false; document.getElementById('addForm').reset();" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-400 hover:text-white transition-colors text-xs font-black uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white transition-colors text-xs font-black uppercase tracking-widest">Deploy</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🌟 Edit Modal --}}
    <div x-show="editMode" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm" style="display:none;">
        <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-8 w-full max-w-lg shadow-2xl relative" @click.away="editMode = false; document.getElementById('editForm').reset();">
            <h3 class="text-2xl font-black text-white mb-6">Modify Global Product</h3>
            <form id="editForm" :action="editUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Product Name</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Category</label>
                    <select name="category" x-model="editData.category" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Price (JOD)</label>
                        <input type="number" step="0.01" name="price" x-model="editData.price" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Update Image</label>
                        <input type="file" name="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-indigo-400 hover:file:bg-slate-700 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                    <textarea name="description" x-model="editData.description" rows="3" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="editMode = false; document.getElementById('editForm').reset();" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-400 hover:text-white transition-colors text-xs font-black uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white transition-colors text-xs font-black uppercase tracking-widest">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
