@extends('layouts.supply')

@section('title', 'Master HQ Command Center')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 mt-8" x-data="hqDashboard()">

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-6 shadow-[0_0_15px_rgba(16,185,129,0.1)]">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl mb-6 shadow-[0_0_15px_rgba(244,63,94,0.1)]">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl mb-6 shadow-[0_0_15px_rgba(244,63,94,0.1)]">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🌟 1. HQ Header --}}
    <div class="bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 border border-slate-800 shadow-xl overflow-hidden relative fade-in-up">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-[60px]"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-[60px]"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-slate-950 rounded-2xl border border-slate-800 shadow-inner flex items-center justify-center text-cyan-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight">Master Supply HQ</h2>
                    <p class="text-xs font-black text-slate-500 mt-1 uppercase tracking-widest text-cyan-500/50">Global Oversight & Load Balancing</p>
                </div>
            </div>

            <div class="flex gap-4">
                <button @click="$refs.addProductModal.classList.remove('hidden')" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-[0_0_15px_rgba(79,70,229,0.3)] transition-all font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Global Product
                </button>
            </div>
        </div>
    </div>

    {{-- 🌟 2. Branch Monitor & Drill Down Matrix --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left: Branches List --}}
        <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl fade-in-up overflow-hidden" style="animation-delay: 0.1s;">
            <div class="p-8 border-b border-slate-800 bg-slate-950/40">
                <h3 class="text-xl font-black text-white tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-amber-500/10 rounded-xl border border-amber-500/20 text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    Regional Nodes
                </h3>
            </div>
            <div class="p-4 space-y-2 relative h-[400px] overflow-y-auto table-scroll">
                @foreach($branches as $branch)
                    <button @click="loadBranchInventory({{ $branch->id }}, '{{ $branch->governorate ?? 'Unknown' }}')"
                            :class="{'bg-slate-800 border-indigo-500/50 shadow-[0_0_15px_rgba(79,70,229,0.1)]': activeBranch === {{ $branch->id }}, 'bg-slate-950 border-slate-800 hover:border-slate-700': activeBranch !== {{ $branch->id }}}"
                            class="w-full text-left p-4 rounded-2xl border transition-all group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center font-black text-xs shadow-inner text-slate-400">
                                    {{ substr($branch->governorate, 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="font-black text-white group-hover:text-indigo-400 transition-colors">{{ $branch->name }}</h4>
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ $branch->governorate ?? 'Unknown' }} Governorate</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-slate-600 group-hover:text-indigo-500 transition-colors group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </button>
                @endforeach
                @if($branches->isEmpty())
                    <div class="p-8 text-center text-slate-500 text-xs font-bold uppercase tracking-widest">No regional branches found.</div>
                @endif
            </div>
        </div>

        {{-- Right: Drill Down Matrix (Load Balancing View) --}}
        <div class="lg:col-span-2 bg-slate-900/60 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl fade-in-up overflow-hidden" style="animation-delay: 0.2s;">
            <div class="p-8 border-b border-slate-800 bg-slate-950/40 flex justify-between items-center">
                <h3 class="text-xl font-black text-white tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-cyan-500/10 rounded-xl border border-cyan-500/20 text-cyan-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    Stock Telemetry <span x-show="activeBranchName" x-text="'- ' + activeBranchName" class="text-slate-400 ml-2"></span>
                </h3>
            </div>

            <div class="relative h-[400px]">
                {{-- Loading State --}}
                <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm z-10" style="display: none;">
                    <div class="w-10 h-10 border-4 border-slate-700 border-t-cyan-500 rounded-full animate-spin"></div>
                </div>

                {{-- Default State --}}
                <div x-show="!activeBranch && !isLoading" class="h-full flex items-center justify-center text-center p-8">
                    <div>
                        <div class="w-16 h-16 bg-slate-950 rounded-2xl flex items-center justify-center mb-4 border border-slate-800 shadow-inner mx-auto text-slate-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Select a regional node<br>to view real-time telemetry.</p>
                    </div>
                </div>

                {{-- Data State --}}
                <div x-show="activeBranch && !isLoading" class="h-full overflow-y-auto table-scroll p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" style="display: none;">
                    <template x-for="item in inventory" :key="item.id">
                        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 flex flex-col justify-between" :class="item.stock <= 5 ? 'border-rose-500/30' : ''">
                            <div>
                                <h5 class="text-white font-black text-sm truncate" x-text="item.name"></h5>
                                <div class="flex items-center gap-2 mb-3 mt-1">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest" x-text="item.category"></p>
                                    <template x-if="item.stock <= 5">
                                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" title="Low Stock Warning"></span>
                                    </template>
                                </div>
                            </div>
                            <div class="flex items-end justify-between mt-auto pt-3 border-t border-slate-800/50">
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Available Stock</span>
                                <span class="px-3 py-1 bg-slate-900 border rounded-lg text-lg font-black" :class="item.stock <= 5 ? 'text-rose-400 border-rose-500/50 shadow-[0_0_10px_rgba(244,63,94,0.2)]' : 'text-emerald-400 border-emerald-500/20'" x-text="item.stock || 0"></span>
                            </div>
                        </div>
                    </template>
                    <div x-show="inventory.length === 0" class="col-span-full py-12 text-center text-slate-500 text-xs font-bold uppercase tracking-widest">
                        No products available in the global catalog.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🌟 3. Global Catalog Manager --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl fade-in-up overflow-hidden mt-12" style="animation-delay: 0.3s;">
        <div class="p-8 md:p-10 border-b border-slate-800 bg-slate-950/40 flex justify-between items-center">
            <h3 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-indigo-500/10 rounded-xl border border-indigo-500/20 text-indigo-400 shadow-[0_0_15px_rgba(79,70,229,0.2)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m-9-4h.01M9 16h.01"></path></svg>
                </div>
                Global Catalog
            </h3>
        </div>

        <div class="p-8 w-full overflow-x-auto table-scroll">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="bg-slate-950/80 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Product</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Category</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Price (JOD)</th>
                        <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @foreach($globalSupplies as $supply)
                        <tr class="hover:bg-slate-800/40 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-slate-950 rounded-xl border border-slate-800 overflow-hidden shrink-0">
                                        @if($supply->image)
                                            <img src="{{ asset('storage/supplies/' . $supply->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex justify-center items-center text-[8px] text-slate-600 font-black uppercase">No Img</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-white truncate max-w-[200px]">{{ $supply->name }}</h4>
                                        <p class="text-[10px] text-slate-500 truncate max-w-[200px]">{{ Str::limit($supply->description, 30) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded bg-slate-950 border border-slate-800 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $supply->category }}</span>
                            </td>
                            <td class="px-6 py-4 text-emerald-400 font-black tracking-tight">
                                {{ number_format($supply->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal({{ json_encode($supply) }})" class="p-2 bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <form action="{{ route('supplies.catalog.destroy', $supply->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this global product? This removes it from ALL regional branches.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(method_exists($globalSupplies, 'hasPages') && $globalSupplies->hasPages())
                <div class="mt-6 border-t border-slate-800 pt-6">
                    {{ $globalSupplies->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modals (Add / Edit) --}}
    <div x-ref="addProductModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm hidden">
        <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-8 w-full max-w-lg shadow-2xl relative" @click.away="$refs.addProductModal.classList.add('hidden')">
            <h3 class="text-2xl font-black text-white mb-6">Deploy Global Product</h3>
            <form action="{{ route('supplies.catalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Product Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Category</label>
                    <select name="category" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="لحوم ومشاوي">لحوم ومشاوي</option>
                        <option value="خضار وفواكه">خضار وفواكه</option>
                        <option value="مقبلات وسلطات">مقبلات وسلطات</option>
                        <option value="أدوات ومعدات الشواء">أدوات ومعدات الشواء</option>
                        <option value="تسالي وحلويات">تسالي وحلويات</option>
                        <option value="مشروبات وثلج">مشروبات وثلج</option>
                        <option value="مستلزمات السفرة والنظافة">مستلزمات السفرة والنظافة</option>
                        <option value="ألعاب وترفيه">ألعاب وترفيه</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Price (JOD)</label>
                        <input type="number" step="0.01" name="price" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Image</label>
                        <input type="file" name="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-indigo-400 hover:file:bg-slate-700">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                    <textarea name="description" rows="3" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="$refs.addProductModal.classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-400 hover:text-white transition-colors text-xs font-black uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white transition-colors text-xs font-black uppercase tracking-widest">Deploy</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="editMode" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm" style="display:none;">
        <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-8 w-full max-w-lg shadow-2xl relative" @click.away="editMode = false">
            <h3 class="text-2xl font-black text-white mb-6">Modify Global Product</h3>
            <form :action="editUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Product Name</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Category</label>
                    <select name="category" x-model="editData.category" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="لحوم ومشاوي">لحوم ومشاوي</option>
                        <option value="خضار وفواكه">خضار وفواكه</option>
                        <option value="مقبلات وسلطات">مقبلات وسلطات</option>
                        <option value="أدوات ومعدات الشواء">أدوات ومعدات الشواء</option>
                        <option value="تسالي وحلويات">تسالي وحلويات</option>
                        <option value="مشروبات وثلج">مشروبات وثلج</option>
                        <option value="مستلزمات السفرة والنظافة">مستلزمات السفرة والنظافة</option>
                        <option value="ألعاب وترفيه">ألعاب وترفيه</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Price (JOD)</label>
                        <input type="number" step="0.01" name="price" x-model="editData.price" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Update Image</label>
                        <input type="file" name="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-indigo-400 hover:file:bg-slate-700">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                    <textarea name="description" x-model="editData.description" rows="3" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="editMode = false" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-400 hover:text-white transition-colors text-xs font-black uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white transition-colors text-xs font-black uppercase tracking-widest">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('hqDashboard', () => ({
        activeBranch: null,
        activeBranchName: '',
        inventory: [],
        isLoading: false,

        editMode: false,
        editUrl: '',
        editData: {},

        loadBranchInventory(id, name) {
            this.activeBranch = id;
            this.activeBranchName = name;
            this.isLoading = true;

            fetch(`/supplies/branch-inventory/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                this.inventory = data.inventory;
                this.isLoading = false;
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                this.isLoading = false;
            });
        },

        openEditModal(supply) {
            this.editData = supply;
            this.editUrl = `/supplies/global-catalog/${supply.id}`;
            this.editMode = true;
        }
    }));
});
</script>
@endsection
