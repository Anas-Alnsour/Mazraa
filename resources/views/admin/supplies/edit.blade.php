@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/60 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-2xl shadow-2xl">
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/10 blur-[80px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Adjust <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-300">Supply</span></h1>
            <p class="text-slate-400 font-medium flex items-center gap-2">
                Modifying: <span class="text-white font-bold bg-slate-900/50 px-3 py-1 rounded-lg border border-slate-700">{{ $supply->name }}</span>
                <span class="text-[10px] text-blue-400 font-mono font-black uppercase tracking-widest bg-blue-500/10 px-2 py-1 rounded border border-blue-500/20">ID: #{{ str_pad($supply->id, 5, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>
        <a href="{{ route('admin.supplies.index') }}" class="relative z-10 px-6 py-3 bg-slate-900/50 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl transition-all border border-slate-700 hover:border-slate-500 flex items-center gap-3 group shadow-lg">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Inventory
        </a>
    </div>

    <form action="{{ route('admin.supplies.update', $supply->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-24">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Left Column: Form Details --}}
            <div class="xl:col-span-8 space-y-8">

                {{-- [SECTION: PRODUCT DETAILS] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl group">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm group-hover:border-blue-500/30 transition-colors">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Core Product Info
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Supply Name --}}
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Supply Name <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $supply->name) }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium" required>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Category <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <input type="text" name="category" value="{{ old('category', $supply->category) }}" list="categories" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-3.5 transition-all shadow-inner font-medium" required>
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
                                <div class="absolute top-4 left-4 text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                </div>
                                <textarea name="description" rows="5" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-12 py-4 resize-none leading-relaxed transition-all shadow-inner font-medium" required>{{ old('description', $supply->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRICING & INVENTORY] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pricing & Inventory
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Price --}}
                        <div class="p-6 bg-slate-900/60 rounded-3xl border border-blue-500/30 shadow-inner group hover:bg-slate-900/80 transition-all relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 rounded-bl-full pointer-events-none"></div>
                            <label class="block text-xs font-black text-blue-400 group-hover:text-blue-300 mb-2 uppercase tracking-widest flex items-center gap-1">Unit Price <span class="text-rose-500">*</span></label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500/50">
                                    <span class="text-xl font-black uppercase">JOD</span>
                                </div>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $supply->price) }}" class="w-full bg-slate-900 border border-blue-500/30 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-16 py-4 text-3xl font-black transition-all shadow-inner" required>
                            </div>
                        </div>

                        {{-- Stock --}}
                        <div class="p-6 bg-slate-900/60 rounded-3xl border border-slate-700/50 shadow-inner group hover:border-blue-500/30 transition-all relative overflow-hidden">
                            <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex items-center gap-1">Available Stock <span class="text-rose-500">*</span></label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <input type="number" name="stock" value="{{ old('stock', $supply->stock) }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none pl-14 py-4 text-2xl font-black transition-all shadow-inner" required>
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
                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Supply Company
                    </h3>

                    <div class="space-y-1">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <select name="company_id" class="w-full bg-slate-900 border border-slate-600 text-blue-400 font-black uppercase tracking-widest text-xs py-4 pl-12 pr-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 appearance-none shadow-inner" required>
                                @if(isset($companies) && $companies->count() > 0)
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $supply->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ $supply->company_id }}" selected>Company ID: {{ $supply->company_id }}</option>
                                @endif
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none opacity-50"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                        </div>
                    </div>
                </div>

                {{-- [SECTION: PRODUCT IMAGE] --}}
                <div class="bg-slate-800/40 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl shadow-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-700 text-xs font-black text-blue-400 uppercase tracking-widest mb-8 shadow-sm">
                        Product Visual
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-300 mb-2 uppercase tracking-widest flex justify-between">
                            Main Display Image
                            <span class="text-[9px] bg-blue-500/20 text-blue-400 px-2 py-1 rounded">Current</span>
                        </label>
                        <div class="relative group h-64 rounded-3xl border-2 border-dashed border-slate-600 flex items-center justify-center hover:border-blue-400 hover:bg-blue-400/5 transition-all overflow-hidden cursor-pointer bg-slate-900/60 shadow-[0_10px_25px_rgba(0,0,0,0.5)]">
                            <img src="{{ $supply->image ? asset('storage/'.$supply->image) : 'https://via.placeholder.com/400' }}" class="w-full h-full object-cover group-hover:scale-110 group-hover:blur-sm transition-all duration-500">

                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10">

                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center p-4">
                                <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center mb-3 text-blue-400 border border-blue-500/30 transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </div>
                                <span class="text-[11px] font-black text-white uppercase tracking-[0.2em] transform translate-y-4 group-hover:translate-y-0 transition-transform">Replace Image</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="pt-4 sticky top-[300px] z-20">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-500 hover:to-indigo-400 text-white font-black rounded-2xl shadow-[0_15px_30px_-10px_rgba(59,130,246,0.5)] transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-xs">
                        Save Adjustments
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
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
    </form>
</div>
@endsection
