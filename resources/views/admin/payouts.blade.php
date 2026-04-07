@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10">

    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Financial Payouts</h1>
            <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Manage outstanding balances and record bank transfers</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-5 rounded-2xl shadow-sm font-bold mb-10 flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-900">Outstanding Vendor Balances</h3>
                    <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest">{{ $vendorsOwed->count() }} Owed</span>
                </div>

                @if($vendorsOwed->count() > 0)
                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Vendor Info</th>
                                    <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Account Type</th>
                                    <th class="px-6 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right border-b border-slate-100">Owed Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($vendorsOwed as $vendor)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-6">
                                            <p class="text-sm font-black text-slate-900">{{ $vendor->name }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $vendor->email }}</p>
                                        </td>
                                        <td class="px-6 py-6">
                                            @php
                                                $badges = [
                                                    'farm_owner' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'transport_company' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'supply_company' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                ];
                                                $badgeClass = $badges[$vendor->role] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                            @endphp
                                            <span class="inline-block px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $badgeClass }}">
                                                {{ str_replace('_', ' ', $vendor->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 text-right">
                                            <button onclick="document.getElementById('user_id').value = '{{ $vendor->id }}'; document.getElementById('amount').value = '{{ $vendor->balance }}'; window.scrollTo({top: 0, behavior: 'smooth'});" class="group flex flex-col items-end justify-center w-full focus:outline-none bg-emerald-50 hover:bg-emerald-100 p-4 rounded-2xl transition-colors border border-emerald-100">
                                                <p class="text-xl font-black text-emerald-600 group-hover:text-emerald-700 transition-colors">{{ number_format($vendor->balance, 2) }} <span class="text-[10px] text-emerald-500 uppercase tracking-widest">JOD</span></p>
                                                <span class="text-[9px] font-bold text-emerald-600/70 group-hover:text-emerald-700 uppercase tracking-widest mt-1 flex items-center gap-1">
                                                    Click to Load <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6 border border-emerald-100">
                            <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900 mb-2">All Settled Up!</h4>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">There are no outstanding balances owed to any vendors.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-slate-900/20 border border-slate-800 p-8 md:p-10 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white mb-2">Record Bank Payout</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-800 pb-4">Log a completed bank transfer</p>

                    <form action="{{ route('admin.payouts.record') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="relative">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Select Vendor</label>
                            <select name="user_id" id="user_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:ring-2 focus:ring-emerald-500 font-bold text-sm appearance-none outline-none">
                                <option value="" disabled selected>-- Choose Vendor --</option>
                                @foreach($vendorsOwed as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }} (Owed: {{ number_format($vendor->balance, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Transfer Amount (JOD)</label>
                            <input type="number" step="0.01" name="amount" id="amount" required min="1" class="w-full bg-slate-800 border border-slate-700 text-white rounded-2xl px-5 py-4 font-black text-xl outline-none">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Bank Reference ID</label>
                            <input type="text" name="reference_id" required placeholder="e.g. TR-99882211" class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-2xl px-5 py-4 font-bold text-sm outline-none">
                        </div>
                        <button type="submit" class="w-full mt-4 bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-black py-4 px-6 rounded-2xl shadow-lg uppercase tracking-widest text-[10px]">
                            Log Transfer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
