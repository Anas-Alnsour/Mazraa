<x-owner-layout>
    <x-slot name="header">Financial Archives</x-slot>

    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
        .table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
        .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
        .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
        .table-scroll::-webkit-scrollbar-thumb:hover { background: #c2a265; }

        /* Native CSS Pagination */
        .custom-pagination nav { display: flex; align-items: center; gap: 0.5rem; }
        .custom-pagination .page-item .page-link { background-color: #0f172a; border: none; color: #64748b; font-size: 11px; font-weight: 900; padding: 0.75rem 1.25rem; border-radius: 0.75rem; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.3); }
        .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover { background-color: #c2a265; color: #020617; }
        .custom-pagination .page-item.active .page-link { background-color: #c2a265; color: #020617; box-shadow: 0 0 20px rgba(194, 162, 101, 0.4); }
        .custom-pagination .page-item.disabled .page-link { background-color: transparent; opacity: 0.3; color: #334155; cursor: not-allowed; box-shadow: none; }
    </style>

    <div class="max-w-[96%] xl:max-w-7xl mx-auto space-y-10 pb-24 animate-god-in" x-data="{ payoutModal: false, isProcessing: false, isSuccess: false }">

        {{-- ==========================================
             HERO & STATUS SECTION
             ========================================== --}}
        <div class="relative bg-slate-900/80 rounded-[3.5rem] p-10 md:p-14 border border-slate-800 backdrop-blur-2xl shadow-2xl overflow-hidden fade-in-up">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-[#c2a265]/10 blur-[120px] rounded-full pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/10 blur-[100px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-10">
                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-black uppercase tracking-[0.2em] mb-6 shadow-[0_0_15px_rgba(16,185,129,0.15)] text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Treasury Dashboard
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black tracking-tighter mb-4 text-white">Revenue <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#c2a265] to-yellow-500">& Payouts</span></h1>
                    <p class="text-slate-400 font-medium text-sm md:text-base leading-relaxed">Manage your farm's earnings, track upcoming clearances, and export your fiscal data for record-keeping.</p>
                </div>

                <div class="grid grid-cols-2 gap-4 shrink-0 w-full md:w-auto">
                    <div class="bg-slate-950/80 backdrop-blur-3xl border border-slate-800 p-8 rounded-[2.5rem] text-center shadow-inner group hover:border-[#c2a265]/40 transition-all">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-500 font-black mb-3 group-hover:text-[#c2a265] transition-colors">Available</p>
                        <p class="text-4xl font-black text-white tracking-tighter leading-none mb-1 group-hover:scale-105 transition-transform">{{ number_format($availableBalance ?? 0, 2) }}</p>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">JOD</span>
                    </div>
                    <div class="bg-slate-950/80 backdrop-blur-3xl border border-slate-800 p-8 rounded-[2.5rem] text-center shadow-inner group hover:border-emerald-500/40 transition-all">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-500 font-black mb-3 group-hover:text-emerald-500 transition-colors">Pending</p>
                        <p class="text-4xl font-black text-slate-300 tracking-tighter leading-none mb-1 group-hover:scale-105 transition-transform">{{ number_format($pendingRevenue ?? 0, 2) }}</p>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">JOD</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==========================================
             ACTIONS BOX
             ========================================== --}}
        <div class="flex flex-col md:flex-row items-center justify-end gap-6 fade-in-up" style="animation-delay: 0.1s;">
            <button @click="payoutModal = true" class="w-full md:w-auto px-8 py-4 bg-gradient-to-r from-[#c2a265] to-[#9a7b42] hover:to-yellow-600 text-[#020617] rounded-2xl shadow-[0_10px_30px_rgba(194,162,101,0.3)] transition-all active:scale-95 flex items-center justify-center gap-3 transform hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[11px] font-black uppercase tracking-widest">Request Manual Payout</span>
            </button>

            <a href="{{ route('owner.financials.export') }}" class="w-full md:w-auto px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-3 border border-slate-700 hover:border-[#c2a265]/50 transform hover:-translate-y-1">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span class="text-[11px] font-black uppercase tracking-widest">Download Data (CSV)</span>
            </a>
        </div>

        {{-- ==========================================
             TRANSACTIONS WITH FILTERS
             ========================================== --}}
        <div class="bg-slate-900/60 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden backdrop-blur-2xl fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-8 py-8 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-8 bg-slate-950/40">
                <div>
                    <h3 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Financial Stream
                    </h3>
                    <p class="text-[10px] font-black text-slate-500 mt-2 uppercase tracking-[0.2em] ml-1">Audit trail of all incoming and outgoing funds.</p>
                </div>

                <div class="flex items-center gap-2 bg-slate-950 p-1.5 rounded-2xl border border-slate-800 shadow-inner overflow-x-auto hide-scrollbar">
                    @foreach(['all' => 'All Time', 'day' => 'Today', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year'] as $key => $label)
                        <a href="{{ route('owner.financials', ['filter' => $key]) }}"
                           class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ (request('filter', 'all') == $key) ? 'bg-[#c2a265] text-[#020617] shadow-[0_0_15px_rgba(194,162,101,0.3)]' : 'text-slate-500 hover:text-white hover:bg-slate-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="w-full overflow-x-auto table-scroll bg-slate-900/20">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead class="bg-slate-950/80 border-b border-slate-800">
                        <tr>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Timestamp</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Transaction Details</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-center">Reference Trace</th>
                            <th class="px-10 py-7 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap text-right">Liquidity Flow</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <p class="text-xs font-black text-white group-hover:text-[#c2a265] transition-colors mb-1">{{ $tx->created_at->format('M d, Y') }}</p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono">{{ $tx->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <p class="text-sm font-black text-slate-300 tracking-tight truncate max-w-[250px] group-hover:text-white transition-colors">{{ $tx->description }}</p>
                                    <p class="text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 mt-2 {{ $tx->transaction_type === 'credit' ? 'text-emerald-400' : 'text-rose-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ $tx->transaction_type === 'credit' ? 'Incoming Yield' : 'Capital Withdrawal' }}
                                    </p>
                                </td>
                                <td class="px-10 py-6 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] shadow-inner group-hover:border-[#c2a265]/40 transition-colors">
                                        {{ str_replace('_', ' ', $tx->reference_type) }} #{{ str_pad($tx->reference_id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-10 py-6 whitespace-nowrap text-right">
                                    <span class="text-2xl font-black tracking-tighter {{ $tx->transaction_type === 'credit' ? 'text-emerald-400' : 'text-rose-500' }}">
                                        {{ $tx->transaction_type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1 opacity-80">JOD</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-10 py-32 text-center">
                                    <div class="flex flex-col items-center justify-center gap-6">
                                        <div class="w-24 h-24 bg-slate-950 rounded-[2rem] flex items-center justify-center border border-slate-800 shadow-inner">
                                            <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-2xl font-black text-white tracking-tight mb-2">Ledger Empty</h4>
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Transactions will propagate here once bookings are finalized.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
                <div class="px-10 py-10 border-t border-slate-800 bg-slate-950/50 flex flex-col md:flex-row items-center justify-between gap-6 shrink-0">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">
                        Displaying records <span class="text-[#c2a265]">{{ $transactions->count() }}</span> of <span class="text-white">{{ $transactions->total() }}</span>
                    </p>
                    <div class="custom-pagination">
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- ==========================================
             PAYOUT MODAL (GOD MODE)
             ========================================== --}}
        <template x-teleport="body">
            @php
                $user = auth()->user();
                $hasBankDetails = !empty($user->bank_name) && !empty($user->iban) && !empty($user->account_holder_name);
                $maskedIban = $user->iban ? '**** **** **** ' . substr($user->iban, -4) : '';
            @endphp

            <div x-show="payoutModal" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6">
                <div x-show="payoutModal" x-transition.opacity class="fixed inset-0 bg-[#020617]/95 backdrop-blur-xl" @click="if(!isProcessing && !isSuccess) payoutModal = false"></div>

                <div x-show="payoutModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-slate-900/90 backdrop-blur-3xl border border-slate-700 rounded-[3rem] shadow-[0_0_60px_rgba(0,0,0,0.8)] overflow-hidden max-w-lg w-full relative z-10 p-10 transform">

                    <div x-show="!isSuccess">
                        <div class="w-20 h-20 bg-emerald-500/10 rounded-[1.5rem] flex items-center justify-center mb-8 border border-emerald-500/20 mx-auto shadow-inner">
                            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-center text-white tracking-tighter mb-2">Liquidity Transfer</h3>

                        @if($hasBankDetails)
                            <p class="text-[11px] font-bold text-slate-400 text-center mb-8 tracking-[0.2em] uppercase">Withdrawing <strong class="text-emerald-400 text-sm">{{ number_format($availableBalance ?? 0, 2) }} JOD</strong> to origin</p>

                            <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 mb-10 shadow-inner">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 block">Destination Routing</span>
                                <p class="text-base font-black text-white mb-1">{{ $user->bank_name }}</p>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $user->account_holder_name }}</p>
                                <p class="text-sm font-black text-[#c2a265] tracking-[0.3em] mt-5 font-mono bg-slate-900 inline-block px-3 py-1.5 rounded-lg border border-slate-800">{{ $maskedIban }}</p>
                            </div>
                        @else
                            <div class="bg-rose-500/10 border border-rose-500/30 rounded-3xl p-8 mb-10 text-center mt-6 shadow-inner">
                                <div class="w-12 h-12 bg-rose-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <p class="text-xl font-black text-rose-500 tracking-tight mb-2">Invalid Routing</p>
                                <p class="text-[10px] font-bold text-slate-400 leading-relaxed uppercase tracking-widest max-w-[250px] mx-auto">Configure your IBAN matrix in Security Hub before requesting funds.</p>
                                <a href="{{ route('owner.profile.edit') }}" class="inline-block mt-6 px-8 py-4 bg-slate-950 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 border border-slate-700 transition-all shadow-lg hover:shadow-xl">Update Config</a>
                            </div>
                        @endif

                        <form action="{{ route('owner.payout.request') }}" method="POST" @submit="isProcessing = true">
                            @csrf
                            <button type="submit" :disabled="isProcessing || {{ ($availableBalance ?? 0) <= 0 ? 'true' : 'false' }} || !{{ $hasBankDetails ? 'true' : 'false' }}" class="w-full py-5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:to-emerald-400 text-slate-950 text-[11px] font-black uppercase tracking-[0.2em] rounded-[1.5rem] transition-all shadow-[0_15px_30px_rgba(16,185,129,0.3)] flex justify-center items-center gap-3 disabled:opacity-30 disabled:cursor-not-allowed hover:-translate-y-1 transform active:scale-95">
                                <span x-show="!isProcessing">Execute Transfer</span>
                                <span x-show="isProcessing" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-slate-950" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0110-8V0C5 0 0 5 0 12h4zm2 5.3A7.9 7.9 0 014 12H0c0 3 1 5.8 3 8l3-2.7z"></path></svg> Processing...
                                </span>
                            </button>
                            <button type="button" @click="payoutModal = false" :disabled="isProcessing" class="w-full py-5 bg-transparent text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all mt-3">Abort Protocol</button>
                        </form>
                    </div>

                    {{-- Success State inside Modal --}}
                    <div x-show="isSuccess" x-cloak class="text-center py-8">
                        <div class="w-24 h-24 bg-emerald-500/20 rounded-[2rem] flex items-center justify-center mb-8 mx-auto border border-emerald-500/40 shadow-[0_0_40px_rgba(16,185,129,0.3)]">
                            <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-white tracking-tight mb-4">Transmission Sent</h3>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed mb-10 max-w-[250px] mx-auto">Funds queued for processing. Clearance expected in 48-72H.</p>
                        <button type="button" @click="payoutModal = false" class="w-full py-5 bg-slate-800 hover:bg-slate-700 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-lg active:scale-95">Acknowledged</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-owner-layout>
