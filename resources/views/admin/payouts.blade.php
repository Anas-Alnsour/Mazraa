@extends('layouts.admin')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    /* Hide number input arrows */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-8 pb-24">

    {{-- 🌟 Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 bg-slate-900/80 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2">Financial <span class="text-amber-400">Payouts</span></h1>
            <p class="text-slate-400 font-medium text-sm">Manage outstanding vendor balances and log bank transfers securely.</p>
        </div>
        <div class="relative z-10">
            <div class="px-6 py-4 bg-slate-950/50 border border-slate-800 rounded-2xl shadow-inner text-right">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Liability</p>
                <p class="text-3xl font-black text-white flex items-baseline justify-end gap-1">
                    {{ number_format($vendorsOwed->sum('balance'), 2) }} <span class="text-amber-400 text-sm font-bold tracking-widest">JOD</span>
                </p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-5 rounded-2xl shadow-sm font-black uppercase tracking-widest text-xs flex items-center gap-4 fade-in-up" style="animation-delay: 0.1s;">
            <div class="bg-emerald-500/20 p-2 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg></div>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- 🌟 LEFT COLUMN: Outstanding Balances Table --}}
        <div class="xl:col-span-2 fade-in-up" style="animation-delay: 0.2s;">
            <div class="bg-slate-900/60 rounded-[2.5rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl h-full flex flex-col">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
                    <h2 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Outstanding Vendors
                    </h2>
                    <span class="bg-amber-500/20 border border-amber-500/30 text-amber-400 py-1.5 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest">{{ $vendorsOwed->count() }} Owed</span>
                </div>

                @if($vendorsOwed->count() > 0)
                    <div class="overflow-x-auto p-4 flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800">
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Entity Info</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Category</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Liability Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($vendorsOwed as $vendor)
                                    <tr class="hover:bg-slate-800/40 transition-colors group cursor-pointer" onclick="document.getElementById('user_id').value = '{{ $vendor->id }}'; document.getElementById('amount').value = '{{ $vendor->balance }}'; window.scrollTo({top: 0, behavior: 'smooth'});">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-slate-950 border border-slate-700 flex items-center justify-center text-white font-black shadow-inner">
                                                    {{ strtoupper(substr($vendor->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-white group-hover:text-amber-400 transition-colors">{{ $vendor->name }}</p>
                                                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">{{ $vendor->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            @php
                                                $badges = [
                                                    'farm_owner' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                    'transport_company' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                    'supply_company' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                                ];
                                                $badgeClass = $badges[$vendor->role] ?? 'bg-slate-700 text-slate-400 border-slate-600';
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $badgeClass }}">
                                                {{ str_replace('_', ' ', $vendor->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <p class="text-xl font-black text-white group-hover:text-amber-400 transition-colors">{{ number_format($vendor->balance, 2) }} <span class="text-[10px] text-slate-500 uppercase tracking-widest ml-1">JOD</span></p>
                                            <span class="text-[9px] font-bold text-amber-500/0 group-hover:text-amber-500/80 uppercase tracking-widest mt-1 flex items-center justify-end gap-1 transition-colors">
                                                Click to Load <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-20 text-center flex-1 flex flex-col justify-center items-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-emerald-500/10 mb-6 border border-emerald-500/20 shadow-[inset_0_0_20px_rgba(16,185,129,0.1)]">
                            <svg class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-3xl font-black text-white mb-2 tracking-tight">Ledger Settled</h4>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Zero outstanding liabilities recorded.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- 🌟 RIGHT COLUMN: Payout Form --}}
        <div class="xl:col-span-1 fade-in-up" style="animation-delay: 0.3s;">
            <div class="bg-[#020617] rounded-[2.5rem] shadow-2xl shadow-black border border-slate-800 p-8 md:p-10 relative overflow-hidden sticky top-8">
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-[80px] pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="w-12 h-12 bg-emerald-500/20 border border-emerald-500/30 rounded-2xl flex items-center justify-center text-emerald-400 mb-6 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Log Transfer</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-8 border-b border-slate-800 pb-6">Record a completed bank wire</p>

                    <form action="{{ route('admin.payouts.record') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Select Vendor --}}
                        <div class="relative">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Entity / Vendor</label>
                            <div class="relative">
                                <select name="user_id" id="user_id" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:ring-2 focus:ring-emerald-500 font-bold text-sm appearance-none outline-none shadow-inner cursor-pointer transition-colors hover:border-slate-600">
                                    <option value="" disabled selected>-- Select Vendor --</option>
                                    @foreach($vendorsOwed as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ number_format($vendor->balance, 2) }} JOD)</option>
                                    @endforeach
                                </select>
                                {{-- <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-500">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                </div> --}}
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Amount Transferred</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-500 font-black text-lg">JOD</span>
                                <input type="number" step="0.01" name="amount" id="amount" required min="1" placeholder="0.00" class="w-full bg-slate-900 border border-slate-700 text-white rounded-2xl pl-16 pr-5 py-4 font-black text-2xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner transition-colors hover:border-slate-600">
                            </div>
                        </div>

                        {{-- Ref ID --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Bank Reference ID</label>
                            <input type="text" name="reference_id" required placeholder="e.g. TR-99882211" class="w-full bg-slate-900 border border-slate-700 text-slate-300 rounded-2xl px-5 py-4 font-bold text-sm outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner transition-colors hover:border-slate-600 placeholder-slate-700">
                        </div>

                        {{-- Submit --}}
<button
 type="submit"
 class="w-full mt-6
 bg-gradient-to-r from-emerald-500 via-emerald-400 to-emerald-500
 hover:from-emerald-400 hover:to-emerald-300
 text-slate-900 font-bold
 py-4 px-6 rounded-2xl
 shadow-lg shadow-emerald-500/30
 hover:shadow-xl hover:shadow-emerald-400/40
 transition-all duration-300 ease-out
 active:scale-95 hover:scale-[1.02]
 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-slate-900
 uppercase tracking-widest text-xs
 flex items-center justify-center gap-2">


<span>Commit Record</span>

<svg
    class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
    fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
</svg>


</button>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
