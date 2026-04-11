@extends('layouts.admin')

@section('title', 'Message Protocol')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

    @media print {
        body { background: white !important; color: black !important; }
        .no-print { display: none !important; }
        .print-box { border: 1px solid #ccc !important; box-shadow: none !important; background: transparent !important; }
        .print-text { color: black !important; }
    }
</style>

<div class="max-w-[96%] xl:max-w-4xl mx-auto space-y-8 pb-24 animate-god-in fade-in-up">

    {{-- 🌟 1. Top Controls (Back & Delete) --}}
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('admin.contacts.index') }}" class="group flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-emerald-400 transition-colors">
            <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 group-hover:border-emerald-500/30 group-hover:bg-emerald-500/10 transition-all shadow-inner">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </div>
            Return to Inbox
        </a>

        <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" onsubmit="return confirm('WARNING: Purge this message permanently?');">
            @csrf @method('DELETE')
            <button type="submit" class="flex items-center gap-2 px-5 py-3 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-xl border border-rose-500/20 transition-all font-black text-[10px] uppercase tracking-[0.2em] shadow-sm hover:shadow-[0_0_20px_rgba(244,63,94,0.4)] active:scale-95 group">
                <svg class="w-4 h-4 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Purge Record
            </button>
        </form>
    </div>

    {{-- 🌟 2. Main Message Interface --}}
    <div class="relative bg-slate-900/80 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl overflow-hidden print-box">

        {{-- Ambient Glows --}}
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 blur-[100px] rounded-full pointer-events-none no-print"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-teal-500/10 blur-[80px] rounded-full pointer-events-none no-print"></div>

        {{-- Top Gradient Border --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-emerald-500 via-teal-400 to-transparent no-print"></div>

        <div class="p-8 md:p-14 relative z-10">

            {{-- Sender Identity Header --}}
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-8 mb-10 pb-10 border-b border-slate-800">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-[1.5rem] bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white font-black text-4xl shadow-[0_0_30px_rgba(16,185,129,0.3)] border border-emerald-400/30 shrink-0 no-print">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/50 border border-slate-700 text-[9px] font-black uppercase tracking-widest mb-2 shadow-inner text-emerald-400 no-print">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Verified Sender
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter mb-1 print-text">{{ $message->name }}</h2>
                        <a href="mailto:{{ $message->email }}" class="text-slate-400 hover:text-emerald-400 font-bold font-mono tracking-widest text-sm transition-colors print-text flex items-center gap-2">
                            <svg class="w-4 h-4 no-print" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $message->email }}
                        </a>
                    </div>
                </div>
                <div class="text-left md:text-right bg-slate-950/50 p-5 rounded-2xl border border-slate-800 shadow-inner print-box">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1.5">Timestamp</p>
                    <p class="text-lg font-black text-white print-text">{{ $message->created_at->format('F j, Y') }}</p>
                    <p class="text-xs text-slate-400 font-bold tracking-widest mt-1 font-mono print-text">{{ $message->created_at->format('H:i:s') }} &bull; {{ $message->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Subject Line --}}
            <div class="mb-10">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-600 no-print" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    Subject Reference
                </p>
                <div class="px-6 py-5 bg-slate-950 rounded-2xl border border-slate-800 shadow-inner print-box">
                    <h3 class="text-xl md:text-2xl font-bold text-white print-text">{{ $message->subject ?? 'General Inquiry / No Subject' }}</h3>
                </div>
            </div>

            {{-- Message Body --}}
            <div class="mb-12">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-600 no-print" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Decrypted Payload
                </p>
                <div class="bg-[#020617] rounded-3xl p-8 border border-slate-800 shadow-inner text-slate-300 text-base md:text-lg font-medium leading-relaxed selection:bg-emerald-500/30 selection:text-white print-box print-text">
                    {!! nl2br(e($message->message)) !!}
                </div>
            </div>

            {{-- 🌟 3. Action Terminal (Footer) --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-800 no-print">
                <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?? 'Your Inquiry' }}" class="flex-1 py-5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:to-teal-400 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-[0_10px_20px_rgba(16,185,129,0.2)] flex items-center justify-center gap-3 active:scale-95 group">
                    <svg class="w-5 h-5 group-hover:scale-110 group-hover:-rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Initiate Secure Reply
                </a>
                <button type="button" onclick="window.print()" class="px-8 py-5 bg-slate-950 hover:bg-slate-800 text-slate-300 hover:text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl border border-slate-700 transition-all shadow-inner flex items-center justify-center gap-3 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Print Record
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
