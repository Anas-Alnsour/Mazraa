@extends('layouts.admin')

@section('title', 'Adjust Supply Node')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    /* God Mode Inputs */
    .admin-input {
        background: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #f8fafc !important;
        transition: all 0.3s ease !important;
        border-radius: 1rem !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
    }
    .admin-input:focus {
        background: rgba(15, 23, 42, 1) !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2), inset 0 2px 4px rgba(0,0,0,0.3) !important;
        outline: none !important;
    }

    /* God Mode Select */
    .dark-select {
        appearance: none;
        background-color: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #fff !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7l5 5 5-5'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        border-radius: 1rem !important;
        transition: all 0.3s ease;
    }
    .dark-select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
        outline: none;
    }
    .dark-select option { background-color: #0f172a; color: #fff; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-8 animate-god-in pb-24 fade-in-up">

    {{-- 🌟 Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Edit Registry
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2">Adjust <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">Supply</span></h1>
            <p class="text-slate-400 font-medium flex items-center gap-3 mt-3">
                <span class="text-white font-bold bg-slate-950 px-4 py-2 rounded-xl border border-slate-800 shadow-inner">{{ $supply->name }}</span>
                <span class="text-[10px] text-blue-400 font-mono font-black uppercase tracking-widest bg-blue-500/10 px-3 py-2 rounded-lg border border-blue-500/20 shadow-[0_0_15px_rgba(59,130,246,0.15)]">ID: #{{ str_pad($supply->id, 5, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>
        <a href="{{ route('admin.supplies.index') }}" class="relative z-10 px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-slate-600 flex items-center justify-center gap-3 shadow-inner active:scale-95">
            <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Inventory
        </a>
    </div>

    <form action="{{ route('admin.supplies.update', $supply->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- 💡 FIXED: Grid layout with items-start to allow right column to be sticky properly --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start relative">

            {{-- 🌟 Left Column: Form Details --}}
            <div class="xl:col-span-8 space-y-8">

                {{-- [SECTION: PRODUCT DETAILS] --}}
                <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-500/5 blur-[80px] rounded-full pointer-events-none"></div>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-blue-400 uppercase tracking-widest mb-8 shadow-inner">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Core Product Info
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                        {{-- Supply Name --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Supply Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $supply->name) }}" class="w-full admin-input pl-12 font-bold text-sm" required>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Category <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <input type="text" name="category" value="{{ old('category', $supply->category) }}" list="categories" class="w-full admin-input pl-12 font-bold text-sm" required>
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
                        <div class="md:col-span-2 space-y-2 mt-4">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Full Description <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute top-5 left-5 text-slate-600 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                </div>
                                <textarea name="description" rows="5" class="w-full admin-input pl-14 py-5 resize-none leading-relaxed font-medium" required>{{ old('description', $supply->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRICING & INVENTORY] --}}
                <div class="bg-slate-900/60 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-blue-400 uppercase tracking-widest mb-8 shadow-inner relative z-10">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pricing & Inventory
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                        {{-- Price --}}
                        <div class="p-8 bg-[#020617] rounded-[2rem] border border-blue-500/30 shadow-[inset_0_2px_15px_rgba(0,0,0,0.5)] group hover:border-blue-500/50 transition-all relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 rounded-bl-[3rem] pointer-events-none"></div>
                            <label class="block text-[10px] font-black text-blue-500 mb-4 uppercase tracking-widest flex items-center gap-2">Unit Price <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500/50">
                                    <span class="text-xl font-black uppercase">JOD</span>
                                </div>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $supply->price) }}" class="w-full bg-transparent border-none text-white rounded-xl focus:ring-0 pl-16 py-2 text-4xl font-black outline-none" required>
                            </div>
                        </div>

                        {{-- Stock --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Available Stock <span class="text-rose-500">*</span></label>
                            <div class="relative group h-[calc(100%-24px)]">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-600 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <input type="number" name="stock" value="{{ old('stock', $supply->stock) }}" class="w-full admin-input h-full pl-16 text-3xl font-black" required>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 🌟 Right Column: Setup & Image (MADE STICKY) 🌟 --}}
            <div class="xl:col-span-4 h-full relative">
                <div class="sticky top-28 space-y-8 pb-10">

                    {{-- [SECTION: COMPANY ASSIGNMENT] --}}
                    <div class="bg-slate-900/90 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-3xl shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                        <h3 class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] text-center mb-8 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Supply Company
                        </h3>

                        <div class="bg-[#020617] p-5 rounded-3xl border border-slate-800 shadow-inner">
                            <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Assign Provider <span class="text-rose-500">*</span></label>
                            <div class="relative z-20">
                                <select name="company_id" class="w-full dark-select font-bold text-xs text-blue-400" required>
                                    @if(isset($companies) && $companies->count() > 0)
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id', $supply->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $supply->company_id }}" selected>Company ID: {{ $supply->company_id }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- [SECTION: PRODUCT IMAGE] --}}
                    <div class="bg-slate-900/60 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-[10px] font-black text-blue-400 uppercase tracking-widest mb-8 shadow-inner">
                            Product Visual
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest flex justify-between items-center ml-1">
                                Main Display Image
                                <span class="text-[8px] bg-blue-500/20 text-blue-400 px-2 py-1 rounded-md">Active</span>
                            </label>
                            <div class="relative h-64 rounded-3xl overflow-hidden group border-2 border-slate-700 bg-[#020617] shadow-inner cursor-pointer">
                                <img src="{{ $supply->image ? asset('storage/'.$supply->image) : 'https://via.placeholder.com/400' }}" class="w-full h-full object-cover group-hover:scale-110 group-hover:blur-sm transition-all duration-500">
                                <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center p-4 backdrop-blur-sm">
                                    <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                    <div class="flex flex-col items-center transform translate-y-4 group-hover:translate-y-0 transition-all">
                                        <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center mb-3 text-blue-400 border border-blue-500/50 shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <span class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Replace Image</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-6">
                        <button type="submit" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-500 hover:to-indigo-400 text-white font-black rounded-[1.25rem] shadow-[0_10px_30px_rgba(59,130,246,0.3)] transition-all flex items-center justify-center gap-3 active:scale-95 uppercase tracking-[0.2em] text-[10px]">
                            Save Adjustments
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        </button>

                        <div class="mt-6 text-center">
                            <a href="{{ route('admin.supplies.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-rose-400 transition-colors uppercase tracking-widest group">
                                <svg class="w-4 h-4 group-hover:-rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Cancel Editing
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
@endsection
