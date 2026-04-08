@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Breadcrumbs & Back --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.contacts.index') }}" class="group flex items-center gap-2 text-slate-400 hover:text-white transition-colors font-bold text-sm">
            <div class="p-2 rounded-xl bg-slate-800 border border-slate-700 group-hover:bg-slate-700 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </div>
            Back to Inbox
        </a>
        <div class="flex items-center gap-3">
             <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                @csrf @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-2xl border border-rose-500/20 transition-all font-black text-[10px] uppercase tracking-widest shadow-lg">
                    Permanent Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Message Body --}}
    <div class="bg-slate-800/50 rounded-[2.5rem] border border-slate-700/50 backdrop-blur-xl overflow-hidden shadow-2xl">
        {{-- Header Decoration --}}
        <div class="h-2 bg-gradient-to-r from-emerald-500 via-emerald-400 to-transparent"></div>
        
        <div class="p-10 md:p-14">
            {{-- Sender Info --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 pb-12 border-b border-slate-700/50">
                <div class="flex items-center gap-6">
                    <div class="h-20 w-20 rounded-[2rem] bg-gradient-to-tr from-emerald-600 to-emerald-400 flex items-center justify-center text-white font-black text-3xl shadow-2xl border-4 border-slate-800">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-white tracking-tighter">{{ $message->name }}</h2>
                        <a href="mailto:{{ $message->email }}" class="text-emerald-400 hover:underline font-bold transition-all">{{ $message->email }}</a>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Received On</p>
                    <p class="text-lg font-black text-white">{{ $message->created_at->format('F j, Y') }}</p>
                    <p class="text-xs text-slate-400 font-medium">{{ $message->created_at->format('h:i A') }} ({{ $message->created_at->diffForHumans() }})</p>
                </div>
            </div>

            {{-- Subject --}}
            <div class="mb-10">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Subject</p>
                <div class="px-6 py-4 bg-slate-900/50 rounded-2xl border border-slate-700/50 shadow-inner">
                    <h3 class="text-xl font-bold text-white">{{ $message->subject ?? 'General Inquiry' }}</h3>
                </div>
            </div>

            {{-- Message Content --}}
            <div class="mb-12">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Message Content</p>
                <div class="bg-white/5 rounded-3xl p-8 border border-white/5 shadow-xl leading-relaxed text-slate-300 text-lg font-medium selection:bg-emerald-500/30">
                    {!! nl2br(e($message->message)) !!}
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-wrap gap-4 pt-8 border-t border-slate-700/50">
                <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?? 'Your Inquiry' }}" class="flex-1 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-2xl transition-all shadow-xl shadow-emerald-900/20 flex items-center justify-center gap-3 active:scale-95 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Compose Reply
                </a>
                <button onclick="window.print()" class="px-8 py-4 bg-slate-700 hover:bg-slate-600 text-white font-black rounded-2xl transition-all shadow-xl flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Print
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
