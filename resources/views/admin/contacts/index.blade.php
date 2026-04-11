@extends('layouts.admin')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .mail-row { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border-left: 4px solid transparent; }
    .mail-row:hover { background: rgba(30, 41, 59, 0.9); transform: translateX(8px); border-left-color: rgba(16, 185, 129, 0.8); box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 10; position: relative;}
    .mail-unread { background: rgba(16, 185, 129, 0.05); border-left-color: rgba(16, 185, 129, 0.4); }
    /* Scrollbar for tables */
    .table-scroll::-webkit-scrollbar { height: 8px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-8 pb-24">

    {{-- 🌟 Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-10 rounded-[3rem] border border-slate-800 backdrop-blur-2xl shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 mb-5 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-black tracking-widest text-emerald-400 uppercase">Communications</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-2">Message <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Inbox</span></h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-3">Manage incoming inquiries, feedback, and support tickets.</p>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="px-8 py-5 bg-slate-950/80 border border-slate-800 rounded-[2rem] shadow-inner text-center min-w-[140px]">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-2">Total</p>
                <p class="text-4xl font-black text-white">{{ $messages->total() }}</p>
            </div>
            <div class="px-8 py-5 bg-emerald-500/10 border border-emerald-500/30 rounded-[2rem] shadow-[inset_0_0_20px_rgba(16,185,129,0.1)] text-center min-w-[140px] relative overflow-hidden">
                <div class="absolute inset-0 bg-emerald-500/10 animate-pulse pointer-events-none"></div>
                <p class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.3em] mb-2 relative z-10">Unread</p>
                <p class="text-4xl font-black text-emerald-400 relative z-10">{{ \App\Models\ContactMessage::where('status', 'unread')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- 🌟 Messages List --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.1s;">
        <div class="w-full overflow-x-auto table-scroll">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-950/80 border-b border-slate-800">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap w-1/4">Sender Profile</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap w-2/5">Subject & Excerpt</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] text-center whitespace-nowrap">Timeline</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] text-center whitespace-nowrap">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($messages as $message)
                        <tr class="mail-row group {{ $message->status === 'unread' ? 'mail-unread' : '' }}">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-tr from-slate-800 to-slate-700 flex items-center justify-center text-white font-black text-xl border border-slate-600 shadow-lg group-hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] group-hover:border-emerald-500/50 transition-all duration-300 shrink-0">
                                        {{ strtoupper(substr($message->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-black text-white text-base group-hover:text-emerald-400 transition-colors tracking-tight truncate">{{ $message->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono mt-1 flex items-center gap-1.5 truncate"><svg class="w-3 h-3 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>{{ $message->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="max-w-md">
                                    <p class="text-sm font-black text-slate-200 mb-1.5 truncate {{ $message->status === 'unread' ? 'text-white' : '' }}">{{ $message->subject ?? 'General Inquiry' }}</p>
                                    <p class="text-xs text-slate-500 truncate font-medium group-hover:text-slate-400 transition-colors italic">"{{ Str::limit($message->message, 90) }}"</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center whitespace-nowrap">
                                <p class="text-xs font-bold text-slate-300 bg-slate-950 inline-block px-3 py-1.5 rounded-lg border border-slate-800">{{ $message->created_at->format('M d, Y') }}</p>
                                <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest mt-2">{{ $message->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-6 text-center whitespace-nowrap">
                                @if($message->status === 'unread')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/30 animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> New
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-slate-800/80 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-700">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('admin.contacts.show', $message->id) }}" class="p-3.5 bg-slate-950 hover:bg-emerald-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 hover:border-emerald-500 transition-all shadow-lg active:scale-95" title="Read Message">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>

                                    @if($message->status === 'unread')
                                    <form action="{{ route('admin.contacts.markAsRead', $message->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-3.5 bg-slate-950 hover:bg-blue-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 hover:border-blue-500 transition-all shadow-lg active:scale-95" title="Mark as Read">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-3.5 bg-slate-950 hover:bg-rose-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 hover:border-rose-500 transition-all shadow-lg active:scale-95" title="Delete Permanent">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center justify-center space-y-6">
                                    <div class="h-28 w-28 rounded-[2.5rem] bg-slate-950 flex items-center justify-center text-slate-700 border border-slate-800 shadow-inner">
                                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-3xl font-black text-white tracking-tight mb-2">Inbox Clear</h3>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-[11px]">No system messages found in the database. You're all caught up!</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="px-10 py-8 bg-slate-950/80 border-t border-slate-800 flex justify-center custom-pagination">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .custom-pagination nav { @apply flex items-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-900 border border-slate-700 text-slate-400 text-[10px] font-black px-5 py-2.5 rounded-xl transition-all hover:bg-slate-800 hover:text-emerald-400 hover:border-emerald-500/50; }
    .custom-pagination .active .page-link { @apply bg-emerald-600 border-emerald-500 text-white shadow-[0_0_15px_rgba(16,185,129,0.4)]; }
</style>
@endsection
