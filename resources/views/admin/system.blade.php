@extends('layouts.admin')

@section('title', 'System Management Configuration')

@section('content')
<style>
    /* Premium Animations */
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Input & Table Polish */
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .table-scroll::-webkit-scrollbar { height: 8px; width: 4px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }

    .premium-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }
</style>

<div class="max-w-[98%] xl:max-w-[94rem] mx-auto py-8 space-y-10 pb-24">

    {{-- 🌟 1. Header Section (Ultra-Modern Dark) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 bg-slate-900/80 p-10 rounded-[3.5rem] border border-slate-800 shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-500/10 blur-[130px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-black tracking-[0.2em] text-emerald-400 uppercase">Core Infrastructure</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-2 leading-none">System <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Control</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-4 ml-1">Configure global parameters and manage identity protocols.</p>
        </div>

        <div class="relative z-10 flex gap-4">
            <div class="bg-slate-950/80 px-8 py-5 rounded-[2rem] border border-slate-800 shadow-inner text-center">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Nodes Active</p>
                <p class="text-3xl font-black text-white tracking-tighter">{{ $users->total() }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-5 rounded-3xl shadow-xl font-black uppercase tracking-widest text-[10px] flex items-center gap-4 fade-in-up">
            <div class="bg-emerald-500/20 p-2.5 rounded-xl"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
            {{ session('success') }}
        </div>
    @endif

    {{-- 🌟 2. Main Dual Column Layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-10 items-start">

        {{-- ⚙️ Side Panel: Global Variables (STICKY) --}}
        <div class="xl:col-span-4 sticky top-8 fade-in-up" style="animation-delay: 0.1s;">
            <div class="premium-card rounded-[3rem] shadow-2xl p-8 md:p-10 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-5 mb-12 border-b border-slate-800 pb-8">
                        <div class="w-14 h-14 bg-slate-950 border border-slate-700 rounded-2xl flex items-center justify-center text-emerald-400 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-white tracking-tight leading-none mb-1">Global Config</h2>
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Platform Core Logic</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.system.update') }}" class="space-y-12">
                        @csrf
                        <div class="bg-slate-950/80 p-8 rounded-[2rem] border border-slate-800 shadow-inner">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.3em] mb-5 ml-1">Commission Protocol (%)</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-6 text-emerald-500 font-black text-3xl transition-transform group-focus-within:scale-110">%</span>
                                <input type="number" step="0.01" name="commission_rate" value="{{ old('commission_rate', $defaultCommission ?? 10) }}"
                                       class="w-full bg-slate-900 border-2 border-slate-800 text-white rounded-[1.5rem] pl-16 pr-5 py-6 font-black text-4xl outline-none focus:border-emerald-500 transition-all shadow-inner">
                            </div>
                            <div class="mt-6 flex gap-3 items-start border-l-2 border-emerald-500/20 pl-4">
                                <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-[10px] text-slate-500 font-bold leading-relaxed uppercase tracking-wider">Automated deduction applied to all marketplace and farm booking settlements.</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-tr from-emerald-600 to-teal-500 hover:to-emerald-400 text-slate-950 font-black py-6 px-6 rounded-[1.5rem] shadow-[0_15px_35px_rgba(16,185,129,0.25)] transition-all transform active:scale-95 uppercase tracking-[0.2em] text-xs flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            Apply Protocol Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 👤 Main Table: Node Registry (2/3 Width) --}}
        <div class="xl:col-span-8 fade-in-up" style="animation-delay: 0.2s;">
            <div class="bg-slate-900/60 rounded-[3.5rem] border border-slate-800 overflow-hidden shadow-2xl flex flex-col w-full h-full">

                <div class="p-10 border-b border-slate-800 bg-slate-950/30 flex flex-col sm:flex-row justify-between items-center gap-8">
                    <div>
                        <h2 class="text-3xl font-black text-white tracking-tight flex items-center gap-4 leading-none">
                            <div class="p-2 bg-indigo-500/10 rounded-xl border border-indigo-500/20 text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            Identity Registry
                        </h2>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mt-3 ml-12">Authorized ecosystem nodes and access levels</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-4 px-8 rounded-2xl text-[11px] uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-indigo-900/30 flex items-center gap-2">
                        Manage All Users
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="w-full overflow-x-auto table-scroll bg-slate-900/40 flex-1">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-slate-950/80">
                            <tr>
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] border-b border-slate-800">Identity Profile</th>
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] text-center border-b border-slate-800">Clearance Role</th>
                                <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] text-right border-b border-slate-800">Network Trace</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-6">
                                            <div class="h-14 w-14 rounded-2xl bg-slate-950 border border-slate-700 flex items-center justify-center text-white font-black text-xl shadow-inner group-hover:border-indigo-500/50 group-hover:text-indigo-400 transition-all shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-lg font-black text-white group-hover:text-indigo-400 transition-colors truncate tracking-tight mb-0.5">{{ $user->name }}</p>
                                                <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-slate-700"></span> ID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-center">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                                                'farm_owner' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                                'supply_company' => 'bg-lime-500/10 text-lime-400 border-lime-500/30',
                                                'transport_company' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                                                'driver' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                                'user' => 'bg-slate-800 text-slate-400 border-slate-700',
                                            ];
                                            $badge = $roleColors[$user->role] ?? $roleColors['user'];
                                        @endphp
                                        <span class="inline-flex px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $badge }} shadow-lg group-hover:scale-105 transition-transform duration-300">
                                            {{ str_replace('_', ' ', $user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <p class="text-[11px] font-bold text-slate-400 font-mono bg-slate-950 px-4 py-2 rounded-xl border border-slate-800 inline-block shadow-inner group-hover:text-white transition-colors">{{ $user->email }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-10 py-32 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-6">
                                            <div class="w-24 h-24 rounded-full bg-slate-950 border border-slate-800 flex items-center justify-center text-slate-800 shadow-inner">
                                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            </div>
                                            <p class="text-xs font-black text-slate-600 uppercase tracking-widest">Protocol error: No identities detected.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 🌟 3. PAGINATION (PRO) --}}
                @if(method_exists($users, 'hasPages') && $users->hasPages())
                    <div class="px-10 py-10 border-t border-slate-800 bg-slate-950/50 flex flex-col md:flex-row items-center justify-between gap-8 shrink-0">
                        <div class="flex flex-col">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1">Telemetry Report</p>
                            <p class="text-xs font-bold text-slate-400">
                                Displaying <span class="text-indigo-400">{{ $users->count() }}</span> of <span class="text-white">{{ $users->total() }}</span> unique identities
                            </p>
                        </div>
                        <div class="custom-pagination">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Pagination Overrides - Absolute Modern Style */
    .pagination { @apply flex items-center gap-2; }
    .page-item .page-link { @apply bg-slate-900 border-none text-slate-500 text-[11px] font-black px-5 py-3 rounded-xl transition-all hover:bg-indigo-600 hover:text-white shadow-lg; }
    .page-item.active .page-link { @apply bg-indigo-600 text-white shadow-[0_0_20px_rgba(99,102,241,0.4)]; }
    .page-item.disabled .page-link { @apply bg-transparent opacity-20 text-slate-700 cursor-not-allowed; }
</style>
@endsection
