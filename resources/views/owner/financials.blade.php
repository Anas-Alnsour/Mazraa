<x-owner-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-extrabold text-[#020617] tracking-tight">Financial Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Track your earnings, view pending payouts, and manage your revenue.</p>
        </div>
    </x-slot>

    <div class="pb-10 max-w-7xl mx-auto space-y-8" x-data="{ payoutModal: false, isProcessing: false, isSuccess: false }">

        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 sm:p-5 flex gap-4 animate-fade-in-up">
            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h4 class="text-sm sm:text-base font-extrabold text-blue-900">Payout Policy</h4>
                <p class="text-xs sm:text-sm text-blue-800 mt-1 leading-relaxed">
                    Earnings are securely held in <span class="font-bold">Pending Clearance</span> and will only move to your <span class="font-bold">Available Balance</span> <span class="underline">after the guest has fully completed their stay</span> and checked out. Future or active bookings cannot be withdrawn yet.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 0.1s;">

            <div class="bg-gradient-to-br from-[#1d5c42] to-[#154531] rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#1d5c42]/50 relative overflow-hidden text-white group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="relative z-10 flex flex-col h-full">
                    <div>
                        <p class="text-sm font-bold text-green-100 uppercase tracking-wider mb-1">Available for Payout</p>
                        <div class="flex items-end gap-2 mb-4">
                            <h3 class="text-4xl font-black tracking-tight">{{ number_format($availableBalance ?? 0, 2) }}</h3>
                            <span class="text-lg font-bold text-green-200 mb-1">JOD</span>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <button @click="payoutModal = true; isSuccess = false;" class="px-5 py-2.5 bg-white text-[#1d5c42] text-xs font-bold rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition-all active:scale-95">
                            Request Payout
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-amber-500/30 transition-colors">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-4 border border-amber-100 text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Pending Clearance</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-extrabold text-[#020617] tracking-tight">{{ number_format($pendingRevenue ?? 0, 2) }} <span class="text-sm text-gray-400 font-bold">JOD</span></h3>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 font-medium mt-auto">Funds from active & upcoming bookings.</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-[#c2a265]/30 transition-colors">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50/50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="w-12 h-12 rounded-2xl bg-[#c2a265]/10 flex items-center justify-center mb-4 border border-[#c2a265]/20 text-[#c2a265]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Lifetime Earnings</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-extrabold text-[#020617] tracking-tight">{{ number_format($lifetimeEarnings ?? 0, 2) }} <span class="text-sm text-gray-400 font-bold">JOD</span></h3>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 font-medium mt-auto">Total revenue cleared through Mazraa.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-[#020617]">Recent Transactions</h3>
                    <p class="text-sm text-gray-500 mt-1">Your latest booking revenues and payouts.</p>
                </div>
                <a href="{{ route('owner.financials.export') }}" target="_blank" class="text-sm font-bold text-[#1d5c42] hover:text-[#154531] transition-colors flex items-center justify-center gap-1 bg-green-50 px-4 py-2 rounded-xl border border-green-100 hover:shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </a>
            </div>

            @if(isset($transactions) && $transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-gray-100 bg-white text-xs font-bold tracking-wider text-gray-500 uppercase">
                                <th class="py-4 px-4">Date</th>
                                <th class="py-4 px-4">Description</th>
                                <th class="py-4 px-4 text-right">Amount</th>
                                <th class="py-4 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 text-sm font-medium text-gray-600">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</td>
                                    <td class="py-4 px-4">
                                        <p class="font-bold text-[#020617] text-sm">{{ $transaction->description ?? 'Farm Booking Revenue' }}</p>
                                        <p class="text-xs text-gray-500">Ref: #{{ $transaction->reference_id ?? rand(10000, 99999) }}</p>
                                    </td>
                                    <td class="py-4 px-4 text-right font-extrabold text-[#1d5c42]">
                                        +{{ number_format($transaction->amount ?? 0, 2) }} JOD
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($transaction->status === 'completed')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Cleared
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-inner">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-[#020617] mb-1">No Transactions Yet</h4>
                    <p class="text-sm text-gray-500 max-w-sm">When your farm receives bookings and payments are processed, they will appear here.</p>
                </div>
            @endif
        </div>

        <template x-teleport="body">
            @php
                $user = auth()->user();
                $hasBankDetails = !empty($user->bank_name) && !empty($user->iban) && !empty($user->account_holder_name);
                $maskedIban = $user->iban ? '**** **** **** ' . substr($user->iban, -4) : '';
            @endphp

            <div x-show="payoutModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">

                <div x-show="payoutModal" x-transition.opacity class="fixed inset-0 bg-[#020617]/80 backdrop-blur-sm" @click="if(!isProcessing && !isSuccess) payoutModal = false"></div>

                <div x-show="payoutModal" x-transition.scale.origin.bottom class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-md w-full relative z-10 p-8 transform transition-all">

                    <div x-show="!isSuccess">
                        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4 border border-green-100 mx-auto">
                            <svg class="w-8 h-8 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-center text-[#020617] mb-2">Request Payout</h3>

                        @if($hasBankDetails)
                            <p class="text-sm text-gray-500 text-center mb-4">You are about to withdraw <strong class="text-[#020617]">{{ number_format($availableBalance ?? 0, 2) }} JOD</strong> to your registered bank account.</p>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Destination</span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-[#020617]">{{ $user->bank_name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->account_holder_name }}</p>
                                <p class="text-sm font-black text-[#1d5c42] tracking-widest mt-1">{{ $maskedIban }}</p>
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-center">
                                <svg class="w-8 h-8 text-red-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="text-sm font-bold text-red-700">Bank Details Required</p>
                                <p class="text-xs text-red-600 mt-1">Please add your IBAN and bank details in the Account Settings before requesting a payout.</p>
                                <a href="{{ route('owner.profile.edit') }}" class="inline-block mt-3 px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors">
                                    Go to Settings
                                </a>
                            </div>
                        @endif

                        <form @submit.prevent="isProcessing = true; setTimeout(() => { isProcessing = false; isSuccess = true; }, 1500)">
                            <button type="submit" :disabled="isProcessing || {{ ($availableBalance ?? 0) <= 0 ? 'true' : 'false' }} || !{{ $hasBankDetails ? 'true' : 'false' }}" class="w-full py-4 bg-[#1d5c42] hover:bg-[#154531] text-white text-sm font-bold rounded-xl transition-all shadow-lg flex justify-center items-center gap-2 mb-3 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isProcessing">Confirm & Withdraw</span>
                                <span x-show="isProcessing" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing...
                                </span>
                            </button>
                            <button type="button" @click="payoutModal = false" :disabled="isProcessing" class="w-full py-4 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-all border border-gray-200">
                                Cancel
                            </button>
                        </form>
                    </div>

                    <div x-show="isSuccess" x-cloak>
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-lg mx-auto">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-center text-[#020617] mb-2">Success!</h3>
                        <p class="text-sm text-gray-500 text-center mb-8">Your payout request has been successfully submitted. It usually takes 2-3 business days to reflect in your account.</p>
                        <button type="button" @click="payoutModal = false" class="w-full py-4 bg-gray-100 hover:bg-gray-200 text-[#020617] text-sm font-bold rounded-xl transition-all">
                            Close Window
                        </button>
                    </div>

                </div>
            </div>
        </template>
    </div>
</x-owner-layout>
