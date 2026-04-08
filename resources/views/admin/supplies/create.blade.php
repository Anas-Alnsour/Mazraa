@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/60 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Deploy New <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-300">Supply</span></h1>
            <p class="text-slate-400 font-medium">Add a new item to the global marketplace inventory.</p>
        </div>
        <a href="{{ route('admin.supplies.index') }}" class="relative z-10 px-6 py-3 bg-slate-900/50 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl transition-all border border-slate-700 hover:border-slate-500 flex items-center gap-3 group shadow-lg">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Inventory
        </a>
    </div>

    <form action="{{ route('admin.supplies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-24">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Left Column: Form Details --}}
            <div class="xl:col-span-8 space-y-8">

                {{-- [SECTION: PRODUCT DETAILS] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl group">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm group-hover:border-emerald-500/30 transition-colors">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Core Product Info
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Supply Name --}}
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Supply Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium placeholder-slate-500" placeholder="e.g. Premium BBQ Charcoal 5kg" required>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Category <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <input type="text" name="category" value="{{ old('category') }}" list="categories" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium placeholder-slate-500" placeholder="Meat, Tools, Vegetables..." required>
                                <datalist id="categories">
                                    <option value="Meats & Poultry">
                                    <option value="Vegetables & Fruits">
                                    <option value="Charcoal & Fuel">
                                    <option value="BBQ Tools">
                                    <option value="Beverages">
                                </datalist>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2 space-y-1 mt-4">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Full Description <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute top-4 left-4 text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                </div>
                                <textarea name="description" rows="5" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-12 py-4 resize-none leading-relaxed transition-all shadow-inner font-medium placeholder-slate-500" placeholder="Detail the item specifications, weight, and contents..." required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRICING & INVENTORY] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pricing & Inventory
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Price --}}
                        <div class="p-6 bg-slate-900/60 rounded-3xl border border-emerald-500/30 shadow-inner group hover:bg-slate-900/80 transition-all relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-bl-full pointer-events-none"></div>
                            <label class="block text-xs font-black text-emerald-400 group-hover:text-emerald-300 mb-2 uppercase tracking-widest flex items-center gap-1">Unit Price <span class="text-rose-500">*</span></label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500/50">
                                    <span class="text-xl font-black uppercase">JOD</span>
                                </div>
                                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full bg-slate-900 border border-emerald-500/30 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-16 py-4 text-3xl font-black transition-all shadow-inner" required>
                            </div>
                        </div>

                        {{-- Stock --}}
                        <div class="p-6 bg-slate-900/60 rounded-3xl border border-slate-700/50 shadow-inner group hover:border-emerald-500/30 transition-all relative overflow-hidden">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Available Stock <span class="text-rose-500">*</span></label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <input type="number" name="stock" value="{{ old('stock') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none pl-14 py-4 text-2xl font-black transition-all shadow-inner" required>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Setup & Image --}}
            <div class="xl:col-span-4 space-y-8">

                {{-- [SECTION: COMPANY ASSIGNMENT] --}}
                <div class="bg-slate-800/80 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl sticky top-8 z-20">
                    <h3 class="block text-xs font-black text-slate-300 uppercase tracking-widest text-center mb-6 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Supply Company
                    </h3>

                    <div class="space-y-1">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <select name="company_id" class="w-full bg-slate-900 border border-slate-600 text-emerald-400 font-black uppercase tracking-widest text-xs py-4 pl-12 pr-4 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 appearance-none shadow-inner" required>
                                <option value="" disabled selected>Assign Provider...</option>
                                @if(isset($companies) && $companies->count() > 0)
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                @else
                                    <option value="1" selected>Admin Inventory</option>
                                @endif
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none opacity-50"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRODUCT IMAGE] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-emerald-400 uppercase tracking-widest mb-8 shadow-sm">
                        Product Visual
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest">Main Display Image <span class="text-rose-500">*</span></label>
                        <div class="relative group h-64 rounded-3xl border-2 border-dashed border-slate-600 flex items-center justify-center hover:border-emerald-400 hover:bg-emerald-400/5 transition-all overflow-hidden cursor-pointer bg-slate-900/60 shadow-inner">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                            <div class="text-center group-hover:scale-110 transition-transform duration-500">
                                <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500/20 group-hover:text-emerald-400 text-slate-500 transition-colors shadow-lg">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest group-hover:text-emerald-400">Upload Product Image</span>
                                <p class="text-[9px] text-slate-500 mt-2 font-bold uppercase">Clear Background Preferred</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="pt-4 sticky top-[300px] z-20">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-black rounded-2xl shadow-[0_15px_30px_-10px_rgba(16,185,129,0.5)] transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-xs">
                        Deploy to Marketplace
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.supplies.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-rose-400 transition-colors uppercase tracking-widest group">
                            <svg class="w-4 h-4 group-hover:-rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Discard Entry
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection
