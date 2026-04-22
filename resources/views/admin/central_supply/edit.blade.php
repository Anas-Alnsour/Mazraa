@extends('layouts.admin')

@section('title', 'Edit Global Supply')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .dark-input { background: #020617; border: 1px solid #1e293b; color: white; transition: all 0.3s; }
    .dark-input:focus { border-color: #8b5cf6; box-shadow: 0 0 0 2px rgba(139,92,246,0.2); outline: none; }
</style>

<div class="max-w-[96%] xl:max-w-4xl mx-auto py-8 space-y-10 pb-24 animate-god-in">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl relative overflow-hidden fade-in-up transition-all hover:border-indigo-500/30">
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-[0_0_20px_rgba(99,102,241,0.2)] shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-white tracking-tighter leading-none">Modify <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">Product</span></h1>
                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[9px] mt-2">Editing: {{ $supply->name }}</p>
            </div>
        </div>
        <div class="relative z-10">
            <a href="{{ route('admin.central-supplies.index') }}" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Back
            </a>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.central-supplies.update', ['central_supply' => $supply->id]) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Product Name</label>
                    <input type="text" name="name" required value="{{ old('name', $supply->name) }}" class="w-full dark-input rounded-2xl px-6 py-4 text-sm font-bold">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-2 ml-2 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Category</label>
                    <select name="category" required class="w-full dark-input rounded-2xl px-6 py-4 text-sm font-bold appearance-none">
                        <option value="لحوم ومشاوي" {{ (old('category', $supply->category) == 'لحوم ومشاوي') ? 'selected' : '' }}>لحوم ومشاوي</option>
                        <option value="خضار وفواكه" {{ (old('category', $supply->category) == 'خضار وفواكه') ? 'selected' : '' }}>خضار وفواكه</option>
                        <option value="مقبلات وسلطات" {{ (old('category', $supply->category) == 'مقبلات وسلطات') ? 'selected' : '' }}>مقبلات وسلطات</option>
                        <option value="أدوات ومعدات الشواء" {{ (old('category', $supply->category) == 'أدوات ومعدات الشواء') ? 'selected' : '' }}>أدوات ومعدات الشواء</option>
                        <option value="تسالي وحلويات" {{ (old('category', $supply->category) == 'تسالي وحلويات') ? 'selected' : '' }}>تسالي وحلويات</option>
                        <option value="مشروبات وثلج" {{ (old('category', $supply->category) == 'مشروبات وثلج') ? 'selected' : '' }}>مشروبات وثلج</option>
                        <option value="مستلزمات السفرة والنظافة" {{ (old('category', $supply->category) == 'مستلزمات السفرة والنظافة') ? 'selected' : '' }}>مستلزمات السفرة والنظافة</option>
                        <option value="ألعاب وترفيه" {{ (old('category', $supply->category) == 'ألعاب وترفيه') ? 'selected' : '' }}>ألعاب وترفيه</option>
                    </select>
                    @error('category') <span class="text-rose-500 text-[10px] font-bold mt-2 ml-2 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Description</label>
                <textarea name="description" required rows="4" class="w-full dark-input rounded-2xl px-6 py-4 text-sm font-medium">{{ old('description', $supply->description) }}</textarea>
                @error('description') <span class="text-rose-500 text-[10px] font-bold mt-2 ml-2 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Base Price (JOD)</label>
                    <div class="relative">
                        <input type="number" name="price" step="0.01" min="0" required value="{{ old('price', $supply->price) }}" class="w-full dark-input rounded-2xl px-6 py-4 text-sm font-bold font-mono pl-14">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <span class="text-indigo-500 font-black text-xs">JOD</span>
                        </div>
                    </div>
                    @error('price') <span class="text-rose-500 text-[10px] font-bold mt-2 ml-2 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Update Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full dark-input rounded-2xl px-4 py-3.5 text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-slate-800 file:text-indigo-400 hover:file:bg-slate-700 transition-all cursor-pointer">
                    @if($supply->image)
                        <p class="text-[9px] font-bold text-slate-500 mt-2 ml-2">Current image will be kept if no new file is selected.</p>
                    @endif
                    @error('image') <span class="text-rose-500 text-[10px] font-bold mt-2 ml-2 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex justify-end">
                <button type="submit" class="px-10 py-5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:to-purple-500 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all shadow-[0_0_20px_rgba(99,102,241,0.3)] active:scale-95 flex items-center gap-3">
                    Save Changes
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
