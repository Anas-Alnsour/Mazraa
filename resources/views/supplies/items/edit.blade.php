@extends('layouts.supply')

@section('title', 'Reconfigure Product')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    /* God Mode Inputs */
    .supply-input {
        background: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #f8fafc !important;
        transition: all 0.3s ease !important;
        border-radius: 1.25rem !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
    }
    .supply-input:focus {
        background: rgba(15, 23, 42, 1) !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2), inset 0 2px 4px rgba(0,0,0,0.3) !important;
        outline: none !important;
    }
    .supply-input::placeholder { color: #475569 !important; font-weight: 500; text-transform: uppercase; letter-spacing: 0.1em; font-size: 10px; }

    /* Custom Input Autofill Dark Mode Fix */
    input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #020617 inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* God Mode Select */
    .dark-select {
        appearance: none;
        background-color: rgba(2, 6, 23, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.8) !important;
        color: #fff !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2310b981' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7l5 5 5-5'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        border-radius: 1.25rem !important;
        transition: all 0.3s ease;
    }
    .dark-select:focus { border-color: #10b981 !important; box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important; outline: none; }
    .dark-select option { background-color: #0f172a; color: #fff; }
</style>

<div class="max-w-[96%] xl:max-w-5xl mx-auto space-y-10 pb-24 animate-god-in fade-in-up">

    {{-- 🌟 Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 md:p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-teal-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-3 shadow-inner text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span> Inventory Management
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Reconfigure <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-400 to-emerald-400">Product</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-3">
                Updating item: <span class="bg-slate-950 text-emerald-400 px-3 py-1.5 rounded-lg border border-slate-800 ml-1 font-black">{{ $item->name }}</span>
            </p>
        </div>

        <a href="{{ route('supplies.items.index') }}" class="relative z-10 px-6 py-4 bg-slate-950 hover:bg-slate-800 text-slate-400 hover:text-emerald-400 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl transition-all border border-slate-800 hover:border-emerald-500/30 flex items-center justify-center gap-3 shadow-inner active:scale-95 group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Return to Inventory
        </a>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-6 shadow-inner backdrop-blur-md">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0 border border-rose-500/30 text-rose-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xs font-black text-rose-400 uppercase tracking-widest mb-2">Update Failed</h3>
                    <ul class="list-disc pl-5 font-bold text-sm text-slate-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- 🌟 Main Form --}}
    <form action="{{ route('supplies.items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative z-10">
        @csrf
        @method('PUT')

        {{-- [SECTION: PRODUCT VISUAL] --}}
        <div class="bg-slate-900/60 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-950/80 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-6 shadow-sm">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Product Visual
            </div>

            <div class="space-y-4">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2 flex justify-between items-center">
                    Main Display Image
                    <span class="text-[8px] bg-slate-800 text-slate-400 px-2 py-1 rounded-md border border-slate-700">Leave blank to retain current image</span>
                </label>

                <div class="flex flex-col sm:flex-row items-center gap-8">
                    {{-- Current Image Preview --}}
                    @if($item->image)
                        <div class="relative w-40 h-40 rounded-[2rem] border-4 border-slate-800 overflow-hidden flex-shrink-0 shadow-[0_0_20px_rgba(0,0,0,0.5)] bg-[#020617] group">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent pointer-events-none"></div>
                            <span class="absolute bottom-3 w-full text-center text-[9px] font-black text-emerald-400 uppercase tracking-widest drop-shadow-md z-10">Active</span>
                        </div>
                    @endif

                    {{-- Upload Area --}}
                    <div class="flex-1 w-full relative group h-40 rounded-[2rem] border-2 border-dashed border-slate-700 bg-[#020617] hover:border-emerald-500 hover:bg-emerald-500/5 transition-all shadow-inner cursor-pointer flex flex-col items-center justify-center" onclick="document.getElementById('image').click()">
                        <input id="image" name="image" type="file" class="hidden" accept="image/*">
                        <div class="text-center transform transition-all duration-500 group-hover:scale-105">
                            <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-500 group-hover:text-emerald-400 group-hover:bg-emerald-500/20 border border-slate-700 group-hover:border-emerald-500/30 transition-colors shadow-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-emerald-400 transition-colors">Upload Replacement</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- [SECTION: CORE DETAILS] --}}
        <div class="bg-slate-900/60 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-teal-500/5 blur-[80px] rounded-full pointer-events-none"></div>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-950/80 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-6 shadow-sm">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Core Specifications
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                {{-- Product Name --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Product Name <span class="text-emerald-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required
                            class="w-full supply-input pl-14 py-4 font-bold text-sm">
                    </div>
                </div>

                {{-- Category --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Category <span class="text-emerald-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors z-10">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <select name="category" id="category" required class="w-full dark-select pl-14 py-4 font-bold text-sm">
                            @foreach(['Meat & BBQ', 'Charcoal & Firewood', 'Beverages & Snacks', 'Cleaning Supplies', 'Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $item->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Full Description <span class="text-emerald-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute top-5 left-5 text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </div>
                        <textarea name="description" id="description" rows="5" required
                            class="w-full supply-input pl-14 py-5 resize-none leading-relaxed font-medium">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- [SECTION: PRICING & INVENTORY] --}}
        <div class="bg-slate-900/60 p-8 md:p-12 rounded-[3rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-950/80 border border-slate-800 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-6 shadow-sm relative z-10">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Pricing & Inventory
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                {{-- Price --}}
                <div class="p-8 bg-[#020617] rounded-[2rem] border border-emerald-500/30 shadow-[inset_0_2px_15px_rgba(0,0,0,0.5)] group hover:border-emerald-500/50 transition-all relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 rounded-bl-[4rem] pointer-events-none"></div>
                    <label class="block text-[10px] font-black text-emerald-500 mb-4 uppercase tracking-widest flex items-center gap-2">Unit Price <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500/50">
                            <span class="text-xl font-black uppercase">JOD</span>
                        </div>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $item->price) }}" min="0" required
                            class="w-full bg-transparent border-none text-white rounded-xl focus:ring-0 pl-16 py-2 text-4xl font-black outline-none">
                    </div>
                </div>

                {{-- Stock --}}
                <div class="space-y-2 flex flex-col h-full justify-end">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-2">Available Stock <span class="text-emerald-500">*</span></label>
                    <div class="relative group h-[calc(100%-24px)] min-h-[4rem]">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-600 group-focus-within:text-emerald-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $item->stock) }}" min="0" required
                            class="w-full supply-input h-full pl-16 text-3xl font-black">
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="pt-8 flex flex-col md:flex-row justify-end gap-5">
            <a href="{{ route('supplies.items.index') }}" class="w-full md:w-auto py-5 px-12 bg-slate-950 text-slate-400 hover:text-white border border-slate-800 hover:border-slate-600 text-[11px] font-black uppercase tracking-[0.2em] rounded-[1.25rem] shadow-inner transition-all active:scale-95 flex items-center justify-center text-center">
                Cancel Operation
            </a>
            <button type="submit" class="w-full md:w-auto py-5 px-12 bg-gradient-to-r from-teal-600 to-emerald-500 hover:to-emerald-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-[1.25rem] shadow-[0_10px_20px_rgba(16,185,129,0.3)] transition-all active:scale-95 flex items-center justify-center gap-3">
                Commit Changes
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </form>
</div>
@endsection
