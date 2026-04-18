@extends('layouts.admin')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .table-scroll::-webkit-scrollbar { height: 8px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-10 pb-24 animate-[fade-in_0.8s_ease-out]">

    {{-- ==========================================
         HERO & PLATFORM METRICS
         ========================================== --}}
    <div class="relative overflow-hidden bg-slate-900/80 rounded-[3rem] p-10 md:p-14 border border-slate-800 backdrop-blur-2xl shadow-2xl fade-in-up">
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
            <div class="max-w-xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-6 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Platform Financial Intelligence
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4">Financial <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-300">Ledger</span></h1>
                <p class="text-slate-400 font-medium leading-relaxed max-w-md text-lg">Real-time settlement monitoring across farm bookings, marketplace supplies, and professional logistics.</p>
            </div>

            <div class="bg-slate-950/80 backdrop-blur-3xl border border-slate-800 p-10 rounded-[3rem] text-center min-w-[320px] shadow-2xl transition-all hover:border-emerald-500/30 group">
                <p class="text-[10px] uppercase tracking-[0.3em] text-slate-500 font-black mb-3">Gross Platform Revenue</p>
                <div class="flex items-baseline justify-center gap-2">
                    <p class="text-6xl font-black text-white tracking-tighter group-hover:scale-105 transition-transform duration-500">{{ number_format($totalRevenue, 2) }}</p>
                    <span class="text-xl font-bold text-emerald-500">JOD</span>
                </div>
                <div class="mt-8 pt-8 border-t border-slate-800 flex items-center justify-center gap-3">
                    <div class="flex -space-x-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></div>
                    </div>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Real-Time Core Balance</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================
         GRANULAR PROFIT SEGMENTATION
         ========================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 fade-in-up" style="animation-delay: 0.1s;">

        <div class="bg-slate-900/60 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-xl hover:border-emerald-500/30 transition-all group overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-slate-950 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-500 shadow-[inset_0_0_15px_rgba(16,185,129,0.1)] group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-white tracking-widest uppercase">Mazraa Net</h3>
                    <p class="text-[10px] font-bold text-emerald-500/60 uppercase">System Profit</p>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-white tracking-tighter">{{ number_format($netProfit, 2) }}</span>
                <span class="text-xs font-bold text-slate-500 uppercase">JOD</span>
            </div>
        </div>

        <div class="bg-slate-900/60 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-xl hover:border-blue-500/30 transition-all group overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-700"></div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-slate-950 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 shadow-[inset_0_0_15px_rgba(59,130,246,0.1)] group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-white tracking-widest uppercase">Owner Share</h3>
                    <p class="text-[10px] font-bold text-blue-500/60 uppercase">Booking Assets</p>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-white tracking-tighter">{{ number_format($ownerShare, 2) }}</span>
                <span class="text-xs font-bold text-slate-500 uppercase">JOD</span>
            </div>
        </div>

        <div class="bg-slate-900/60 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-xl hover:border-amber-500/30 transition-all group overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-700"></div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-slate-950 border border-amber-500/20 rounded-2xl flex items-center justify-center text-amber-500 shadow-[inset_0_0_15px_rgba(245,158,11,0.1)] group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-white tracking-widest uppercase">Provider Share</h3>
                    <p class="text-[10px] font-bold text-amber-500/60 uppercase">Logistics & Supply</p>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-white tracking-tighter">{{ number_format($providerShare, 2) }}</span>
                <span class="text-xs font-bold text-slate-500 uppercase">JOD</span>
            </div>
        </div>

        <div class="bg-slate-900/60 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-xl hover:border-indigo-500/30 transition-all group overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-slate-950 border border-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 shadow-[inset_0_0_15px_rgba(99,102,241,0.1)] group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-white tracking-widest uppercase">System Net</h3>
                    <p class="text-[10px] font-bold text-indigo-500/60 uppercase">Fee Breakdown</p>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest py-1 border-b border-slate-800">
                    <span class="text-slate-500">Logistics</span>
                    <span class="text-indigo-400">+{{ number_format($transportProfit, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest py-1 border-b border-slate-800">
                    <span class="text-slate-500">Market</span>
                    <span class="text-amber-400">+{{ number_format($supplyProfit, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest py-1">
                    <span class="text-slate-500">Farms</span>
                    <span class="text-blue-400">+{{ number_format($farmProfit, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================
         LIVE TRANSACTION STREAM
         ========================================== --}}
    <div class="bg-slate-900/80 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl overflow-hidden fade-in-up" style="animation-delay: 0.2s;">
        <div class="px-10 py-10 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-8 bg-slate-950/30">
            <div>
                <h3 class="text-2xl font-black text-white tracking-tight">Ledger Stream</h3>
                <p class="text-xs font-bold text-slate-500 mt-2 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    High-Fidelity Transaction Logging
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                {{-- Timeframe Filter --}}
                <form action="{{ route('admin.financials') }}" method="GET" id="filterForm">
                    <div class="relative group">
                        <select name="filter" onchange="this.form.submit()"
                                class="bg-slate-950 border border-slate-800 text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-6 pr-12 py-4 appearance-none cursor-pointer transition-all hover:border-slate-700 shadow-inner">
                            <option value="all" {{ ($filter ?? 'all') == 'all' ? 'selected' : '' }}>Filter: All Time</option>
                            <option value="daily" {{ ($filter ?? '') == 'daily' ? 'selected' : '' }}>Filter: Today</option>
                            <option value="weekly" {{ ($filter ?? '') == 'weekly' ? 'selected' : '' }}>Filter: This Week</option>
                            <option value="monthly" {{ ($filter ?? '') == 'monthly' ? 'selected' : '' }}>Filter: This Month</option>
                            <option value="yearly" {{ ($filter ?? '') == 'yearly' ? 'selected' : '' }}>Filter: This Year</option>
                        </select>
                        {{-- <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-500 group-hover:text-emerald-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                        </div> --}}
                    </div>
                </form>

                {{-- CSV Export --}}
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-[0_10px_20px_rgba(16,185,129,0.2)] hover:shadow-[0_15px_30px_rgba(16,185,129,0.3)] transform active:scale-95 group">
                    <svg class="w-4 h-4 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export Ledger
                </a>
            </div>
        </div>

        <div class="w-full overflow-x-auto table-scroll">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="bg-slate-950/80">
                    <tr>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-800 whitespace-nowrap">Timestamp</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-800 whitespace-nowrap">Account Entity</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-800 whitespace-nowrap">Reference Trace</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-800 whitespace-nowrap">Journal Entry</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-800 whitespace-nowrap text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-slate-800/40 transition-all group">
                            <td class="px-10 py-6 whitespace-nowrap">
                                <p class="text-xs font-black text-slate-400 group-hover:text-emerald-400 transition-colors">{{ $tx->created_at->format('M d, H:i') }}</p>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-950 border border-slate-700 flex items-center justify-center text-[12px] font-black text-slate-400 group-hover:border-emerald-500/50 group-hover:text-emerald-400 transition-all uppercase shadow-inner">
                                        {{ substr($tx->user->name ?? 'S', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-white leading-tight mb-1">{{ $tx->user->name ?? 'Vault System' }}</p>
                                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest group-hover:text-emerald-500/80 transition-colors">{{ $tx->user->role ?? 'Core' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-950 text-slate-400 text-[10px] font-black border border-slate-800 uppercase tracking-widest shadow-inner group-hover:border-emerald-500/30 transition-all">
                                    {{ str_replace('_', ' ', $tx->reference_type) }} #{{ $tx->reference_id }}
                                </span>
                            </td>
                            <td class="px-10 py-6 max-w-sm">
                                <p class="text-xs font-bold text-slate-500 truncate group-hover:text-slate-300 transition-colors">{{ $tx->description }}</p>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap text-right">
                                <div class="inline-flex flex-col items-end gap-1">
                                    <span class="text-xl font-black tracking-tighter {{ $tx->transaction_type === 'credit' ? 'text-emerald-400' : 'text-rose-500' }}">
                                        {{ $tx->transaction_type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                                        <span class="text-[10px] font-bold tracking-widest uppercase">JOD</span>
                                    </span>
                                    <span class="text-[8px] font-black text-slate-600 uppercase tracking-[0.2em] px-2 py-0.5 rounded bg-slate-950 border border-slate-800">{{ $tx->transaction_type === 'credit' ? 'Inflow' : 'Outflow' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center justify-center gap-6">
                                    <div class="w-24 h-24 bg-slate-950 border border-slate-800 rounded-[2rem] flex items-center justify-center text-slate-700 shadow-inner">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-black text-white tracking-tight mb-2">Ledger Inactive</h3>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-[11px]">No active transactions in this segment.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentTransactions->hasPages())
            <div class="px-10 py-8 bg-slate-950/80 border-t border-slate-800 flex justify-center custom-pagination">
                {{ $recentTransactions->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .custom-pagination nav { @apply flex items-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-900 border border-slate-800 text-slate-400 text-[10px] font-black px-5 py-3 rounded-xl transition-all hover:bg-slate-800 hover:text-emerald-400 hover:border-emerald-500/50; }
    .custom-pagination .active .page-link { @apply bg-emerald-600 border-emerald-600 text-white shadow-[0_0_20px_rgba(16,185,129,0.4)]; }
</style>
@endsection
