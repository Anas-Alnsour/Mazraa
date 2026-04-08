@extends('layouts.driver')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

    {{-- ==========================================
         HERO SECTION (SUPPLY HISTORY THEME)
         ========================================== --}}
    <div class="relative bg-gradient-to-r from-slate-800 to-teal-900 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl border border-teal-800/30 overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-400/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-widest mb-6 backdrop-blur-md">
                <svg class="w-3 h-3 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Archive
            </div>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-4">Delivery <span class="text-teal-400/80">History</span></h1>
            <p class="text-slate-400 font-medium max-w-md leading-relaxed">Review your completed supply drops and logistical milestones. Great work keeping the farms stocked!</p>
        </div>
    </div>

    {{-- ==========================================
         HISTORY GRID
         ========================================== --}}

    @if($groupedHistory->isEmpty())
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-[2.5rem] border border-slate-700/50 border-dashed p-16 text-center">
            <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-700 shadow-inner">
                <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h3 class="text-xl font-black text-white tracking-tight">Archives empty</h3>
            <p class="mt-2 text-slate-400 font-medium">No completed deliveries found in your account yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($groupedHistory as $invoiceId => $items)
                @php $firstItem = $items->first(); @endphp
                <div class="group bg-slate-800/40 hover:bg-slate-800/60 transition-all rounded-[2.5rem] border border-slate-700/50 p-8 flex flex-col shadow-lg">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-teal-400/80 mb-1">Invoice ID</p>
                            <h3 class="text-2xl font-black text-white tracking-tight">{{ $invoiceId }}</h3>
                        </div>
                        <div class="bg-emerald-500/10 text-emerald-400 text-[10px] font-black px-3 py-1.5 rounded-xl border border-emerald-500/20 uppercase tracking-widest">
                            Delivered
                        </div>
                    </div>

                    <div class="space-y-6 flex-grow">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Destination</p>
                                <p class="text-sm font-bold text-slate-200">{{ $firstItem->booking->farm->name ?? 'N/A' }}</p>
                            </div>
                            <div class="space-y-1 text-right">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Completed On</p>
                                <p class="text-sm font-bold text-slate-200">{{ $firstItem->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-900/40 rounded-2xl p-4 border border-slate-700/30">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3">Manifest Summary</p>
                            <div class="space-y-2">
                                @foreach($items as $item)
                                    <div class="flex justify-between items-center text-xs font-bold text-slate-400">
                                        <span>{{ $item->quantity }}x {{ $item->supply->name }}</span>
                                        <span class="text-[10px] text-slate-600">Verified</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-700/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-[10px] font-black text-teal-400 border border-slate-600">
                                {{ substr($firstItem->user->name ?? 'G', 0, 1) }}
                            </div>
                            <span class="text-xs font-bold text-slate-400">{{ $firstItem->user->name ?? 'Guest' }}</span>
                        </div>
                        <span class="text-xs font-black text-white">{{ number_format($items->sum('total_price'), 2) }} JOD</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
</style>
@endsection
