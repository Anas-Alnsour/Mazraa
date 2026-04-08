<x-owner-layout>
    @section('title', 'Financial Archives')

    <div class="max-w-7xl mx-auto space-y-10 pb-20 animate-fade-in-up" x-data="{ payoutModal: false, isProcessing: false, isSuccess: false }">

        {{-- ==========================================
             HERO & STATUS SECTION
             ========================================== --}}
        <div class="relative bg-gradient-to-br from-[#020617] via-[#1e293b] to-[#020617] rounded-[3rem] p-10 md:p-14 text-white shadow-2xl overflow-hidden border border-slate-700/50">
            <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/5 rounded-full blur-[100px] -mr-32 -mt-32 pointer-events-none"></div>
            <div class="absolute bottom-0 left-1/4 w-64 h-64 bg-amber-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-10">
                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-widest mb-6 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Treasury Dashboard
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4 text-white">Revenue <span class="text-slate-400">& Payouts</span></h1>
                    <p class="text-slate-400 font-medium leading-relaxed">Manage your farm's earnings, track upcoming clearances, and export your fiscal data for record-keeping.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 backdrop-blur-2xl border border-white/10 p-6 rounded-3xl text-center shadow-xl">
                        <p class="text-[9px] uppercase tracking-widest text-slate-500 font-black mb-1 leading-none">Available</p>
                        <p class="text-3xl font-black text-white tracking-tighter">{{ number_format($availableBalance, 2) }} <span class="text-xs text-emerald-400">JOD</span></p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-2xl border border-white/10 p-6 rounded-3xl text-center shadow-xl">
                        <p class="text-[9px] uppercase tracking-widest text-slate-500 font-black mb-1 leading-none">Pending</p>
                        <p class="text-3xl font-black text-slate-300 tracking-tighter">{{ number_format($pendingRevenue, 2) }} <span class="text-xs text-slate-500">JOD</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==========================================
             ACTIONS BOX
             ========================================== --}}
        <div class="flex flex-col md:flex-row items-center gap-6">
            <button @click="payoutModal = true" class="w-full md:w-auto px-8 py-5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-[2rem] shadow-xl shadow-emerald-900/10 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-black uppercase tracking-widest">Request Manual Payout</span>
            </button>

            <a href="{{ route('owner.financials.export') }}" class="w-full md:w-auto px-8 py-5 bg-slate-100 hover:bg-slate-200 text-slate-900 rounded-[2rem] shadow-lg transition-all active:scale-95 flex items-center justify-center gap-3 border border-slate-200/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span class="text-xs font-black uppercase tracking-widest">Download Data (CSV)</span>
            </a>
        </div>

        {{-- ==========================================
             TRANSACTIONS WITH FILTERS
             ========================================== --}}
        <div class="bg-white rounded-[3rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden">
            <div class="px-10 py-10 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Financial Stream</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1">Audit trail of all incoming and outgoing funds.</p>
                </div>

                <div class="flex items-center gap-2 bg-slate-100/50 p-1.5 rounded-2xl border border-slate-100">
                    @foreach(['all' => 'All Time', 'day' => 'Today', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year'] as $key => $label)
                        <a href="{{ route('owner.financials', ['filter' => $key]) }}" 
                           class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ (request('filter', 'all') == $key) ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-x-auto selection:bg-emerald-100 selection:text-emerald-900">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Timestamp</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Transaction Details</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Ref</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Flow</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <p class="text-xs font-black text-slate-900">{{ $tx->created_at->format('M d, Y') }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase leading-none mt-1">{{ $tx->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-10 py-6">
                                    <p class="text-sm font-black text-slate-800 tracking-tight">{{ $tx->description }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-1 mt-1">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $tx->transaction_type === 'credit' ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                        {{ $tx->transaction_type === 'credit' ? 'Incoming Payment' : 'Withdrawal' }}
                                    </p>
                                </td>
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <span class="text-[9px] font-black px-2.5 py-1.5 rounded-xl bg-slate-900 text-white uppercase tracking-tighter">
                                        {{ str_replace('_', ' ', $tx->reference_type) }} #{{ $tx->reference_id }}
                                    </span>
                                </td>
                                <td class="px-10 py-6 whitespace-nowrap text-right">
                                    <span class="text-lg font-black {{ $tx->transaction_type === 'credit' ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ $tx->transaction_type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                                        <span class="text-[10px] font-bold ml-1 opacity-50">JOD</span>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-10 py-24 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100 shadow-inner">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-black text-slate-900 tracking-tight">No funds recorded</h4>
                                    <p class="text-sm font-bold text-slate-400 mt-2">Transactions will appear here once bookings are finalized.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="px-10 py-10 bg-slate-50/30 border-t border-slate-50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        {{-- ==========================================
             PAYOUT MODAL
             ========================================== --}}
        <template x-teleport="body">
            @php
                $user = auth()->user();
                $hasBankDetails = !empty($user->bank_name) && !empty($user->iban) && !empty($user->account_holder_name);
                $maskedIban = $user->iban ? '**** **** **** ' . substr($user->iban, -4) : '';
            @endphp

            <div x-show="payoutModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div x-show="payoutModal" x-transition.opacity class="fixed inset-0 bg-slate-900/95 backdrop-blur-md" @click="if(!isProcessing && !isSuccess) payoutModal = false"></div>
                <div x-show="payoutModal" x-transition.scale.origin.bottom class="bg-white rounded-[3rem] shadow-2xl overflow-hidden max-w-md w-full relative z-10 p-10 transform transition-all border border-slate-200">

                    <div x-show="!isSuccess">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-6 border border-emerald-100 mx-auto">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-center text-slate-900 tracking-tight mb-2">Request Payout</h3>

                        @if($hasBankDetails)
                            <p class="text-sm font-bold text-slate-500 text-center mb-8">Withdrawing <strong class="text-slate-900">{{ number_format($availableBalance, 2) }} JOD</strong> to your registered account.</p>
                            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 mb-8">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block">Destination Bank</span>
                                <p class="text-sm font-black text-slate-900">{{ $user->bank_name }}</p>
                                <p class="text-[10px] font-bold text-slate-500 mt-0.5">{{ $user->account_holder_name }}</p>
                                <p class="text-sm font-black text-emerald-600 tracking-widest mt-4">{{ $maskedIban }}</p>
                            </div>
                        @else
                            <div class="bg-rose-50 border border-rose-100 rounded-[1.5rem] p-8 mb-8 text-center">
                                <svg class="w-10 h-10 text-rose-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="text-lg font-black text-rose-900">Missing Details</p>
                                <p class="text-sm font-bold text-rose-700/70 mt-1 leading-relaxed">Update your IBAN and bank details in Account Settings before requesting funds.</p>
                                <a href="{{ route('owner.profile.edit') }}" class="inline-flex mt-6 px-6 py-3 bg-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-rose-700 transition-all">
                                    Update Profile
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('owner.payout.request') }}" method="POST" @submit="isProcessing = true">
                            @csrf
                            <button type="submit" :disabled="isProcessing || {{ $availableBalance <= 0 ? 'true' : 'false' }} || !{{ $hasBankDetails ? 'true' : 'false' }}" class="w-full py-5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-widest rounded-2xl transition-all shadow-xl flex justify-center items-center gap-3 disabled:opacity-30 disabled:cursor-not-allowed">
                                <span x-show="!isProcessing">Confirm & Withdraw</span>
                                <span x-show="isProcessing" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0110-8V0C5 0 0 5 0 12h4zm2 5.3A7.9 7.9 0 014 12H0c0 3 1 5.8 3 8l3-2.7z"></path></svg>
                                    Processing...
                                </span>
                            </button>
                            <button type="button" @click="payoutModal = false" :disabled="isProcessing" class="w-full py-5 bg-transparent text-slate-400 text-xs font-black uppercase tracking-widest hover:text-slate-600 transition-all mt-4">
                                Cancel Request
                            </button>
                        </form>
                    </div>

                    <div x-show="isSuccess" x-cloak class="text-center">
                        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-8 mx-auto border-4 border-white shadow-xl">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight mb-4">Transmission Sent</h3>
                        <p class="text-sm font-bold text-slate-500 leading-relaxed mb-10">Your payout request has been queued. Expect the funds in your designated account within 48-72 business hours.</p>
                        <button type="button" @click="payoutModal = false" class="w-full py-5 bg-slate-100 hover:bg-slate-200 text-slate-900 text-xs font-black uppercase tracking-widest rounded-2xl transition-all">
                            Acknowledged
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-owner-layout>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    
    /* Custom Pagination Styling */
    .pagination { @apply flex items-center gap-1; }
    .page-item { @apply inline-flex; }
    .page-link { @apply px-4 py-2 text-xs font-black rounded-xl transition-all border border-transparent; }
    .active .page-link { @apply bg-emerald-600 text-white shadow-lg shadow-emerald-200 border-emerald-600; }
    .page-item:not(.active) .page-link { @apply text-slate-500 hover:bg-white hover:border-slate-200 hover:text-emerald-600; }
</style>

