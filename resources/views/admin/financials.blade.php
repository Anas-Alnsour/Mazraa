@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 space-y-10 animate-fade-in-up">
    
    {{-- ==========================================
         HERO & TOTAL REVENUE
         ========================================== --}}
    <div class="relative bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a] rounded-[3rem] p-10 md:p-14 text-white shadow-2xl overflow-hidden border border-slate-700/50">
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/5 rounded-full blur-[100px] -mr-32 -mt-32 pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-64 h-64 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-10">
            <div class="max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-widest mb-6 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Financial Hub
                </div>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4">Platform <span class="text-slate-400">Ledger</span></h1>
                <p class="text-slate-400 font-medium leading-relaxed">System-wide monitoring of platform revenue streams, marketplace commissions, and logistical profits.</p>
            </div>

            <div class="bg-white/5 backdrop-blur-2xl border border-white/10 p-10 rounded-[2.5rem] text-center min-w-[280px] shadow-2xl group transition-all hover:border-white/20">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-2 leading-none">Grand Total Profit</p>
                <div class="flex items-baseline justify-center gap-2">
                    <p class="text-6xl font-black text-white tracking-tighter">{{ number_format($totalCombinedProfit, 2) }}</p>
                    <span class="text-lg font-bold text-emerald-400">JOD</span>
                </div>
                <div class="mt-6 pt-6 border-t border-white/5 flex items-center justify-center gap-2">
                    <span class="text-[10px] font-black text-emerald-400 uppercase">Live Accumulation</span>
                    <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================
         PROFIT SEGMENTATION
         ========================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Farms Section -->
        <div class="group bg-white rounded-[2.5rem] p-8 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] border border-slate-100 transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Farm Bookings</h3>
                    <p class="text-xs font-bold text-slate-400">15% Platform Commission</p>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($farmProfit, 2) }}</span>
                <span class="text-sm font-bold text-slate-400">JOD</span>
            </div>
        </div>

        <!-- Marketplace Section -->
        <div class="group bg-white rounded-[2.5rem] p-8 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] border border-slate-100 transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-amber-50 border border-amber-100 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Supply Market</h3>
                    <p class="text-xs font-bold text-slate-400">Supplier Commissions</p>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($supplyProfit, 2) }}</span>
                <span class="text-sm font-bold text-slate-400">JOD</span>
            </div>
        </div>

        <!-- Logistics Section -->
        <div class="group bg-white rounded-[2.5rem] p-8 shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] border border-slate-100 transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Transports</h3>
                    <p class="text-xs font-bold text-slate-400">Logistics Net Profit</p>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($transportProfit, 2) }}</span>
                <span class="text-sm font-bold text-slate-400">JOD</span>
            </div>
        </div>
    </div>

    {{-- ==========================================
         TRANSACTION HISTORY
         ========================================== --}}
    <div class="bg-white rounded-[2.5rem] shadow-[0_15px_40px_-10px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-900">Live Transaction Stream</h3>
                <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Real-time Financial Logging</p>
            </div>
        </div>

        <div class="overflow-x-auto selection:bg-indigo-100 selection:text-indigo-900">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Timestamp</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Entity</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Reference</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Description</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-10 py-5 whitespace-nowrap">
                                <span class="text-xs font-bold text-slate-600">{{ $tx->created_at->format('M d, H:i') }}</span>
                            </td>
                            <td class="px-10 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-600 border border-slate-200 uppercase">
                                        {{ substr($tx->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-900 leading-none">{{ $tx->user->name ?? 'System' }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">{{ $tx->user->role ?? 'Core' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-5 whitespace-nowrap">
                                <span class="text-[10px] font-black px-2 py-1 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-tighter">
                                    {{ str_replace('_', ' ', $tx->reference_type) }} #{{ $tx->reference_id }}
                                </span>
                            </td>
                            <td class="px-10 py-5 max-w-xs">
                                <p class="text-xs font-bold text-slate-500 truncate">{{ $tx->description }}</p>
                            </td>
                            <td class="px-10 py-5 whitespace-nowrap text-right">
                                <span class="text-sm font-black {{ $tx->transaction_type === 'credit' ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $tx->transaction_type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                                    <span class="text-[10px] font-bold ml-1">JOD</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-20 text-center">
                                <p class="text-slate-400 font-bold">No transactions found in this period.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentTransactions->hasPages())
            <div class="px-10 py-8 bg-slate-50/30 border-t border-slate-50">
                {{ $recentTransactions->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    
    /* Custom Pagination Styling */
    .pagination { @apply flex items-center gap-1; }
    .page-item { @apply inline-flex; }
    .page-link { @apply px-4 py-2 text-xs font-black rounded-xl transition-all border border-transparent; }
    .active .page-link { @apply bg-indigo-600 text-white shadow-lg shadow-indigo-200 border-indigo-600; }
    .page-item:not(.active) .page-link { @apply text-slate-500 hover:bg-white hover:border-slate-200 hover:text-indigo-600; }
</style>
@endsection

