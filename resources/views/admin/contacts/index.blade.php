@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/50 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Message <span class="text-emerald-400">Inbox</span></h1>
            <p class="text-slate-400 font-medium">Manage incoming inquiries and support requests.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-6 py-3 bg-slate-900/50 border border-slate-700 rounded-2xl">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Messages</p>
                <p class="text-2xl font-black text-white">{{ $messages->total() }}</p>
            </div>
            <div class="px-6 py-3 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                <p class="text-[10px] font-black text-emerald-500/70 uppercase tracking-widest mb-1">Unread</p>
                <p class="text-2xl font-black text-emerald-400">{{ \App\Models\ContactMessage::where('status', 'unread')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Messages Table --}}
    <div class="bg-slate-800/30 rounded-[2.5rem] border border-slate-700/50 overflow-hidden backdrop-blur-md shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-700">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sender</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Subject & Preview</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Date</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($messages as $message)
                        <tr class="group hover:bg-slate-700/30 transition-all duration-300 {{ $message->status === 'unread' ? 'bg-emerald-500/5' : '' }}">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-slate-700 to-slate-600 flex items-center justify-center text-white font-black text-lg border border-slate-600 group-hover:border-emerald-500/50 transition-colors shadow-lg">
                                        {{ strtoupper(substr($message->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-white group-hover:text-emerald-400 transition-colors">{{ $message->name }}</p>
                                        <p class="text-xs text-slate-500 font-medium">{{ $message->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="max-w-md">
                                    <p class="text-sm font-bold text-slate-200 mb-1 line-clamp-1">{{ $message->subject ?? 'General Inquiry' }}</p>
                                    <p class="text-xs text-slate-500 line-clamp-1 italic">"{{ Str::limit($message->message, 80) }}"</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <p class="text-xs font-bold text-slate-300">{{ $message->created_at->format('M d, Y') }}</p>
                                <p class="text-[10px] text-slate-500 font-medium mt-1">{{ $message->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($message->status === 'unread')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        New
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-700/50 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-600/50">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('admin.contacts.show', $message->id) }}" class="p-2.5 bg-slate-800 hover:bg-emerald-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-all shadow-xl" title="View Message">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    
                                    @if($message->status === 'unread')
                                    <form action="{{ route('admin.contacts.markAsRead', $message->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-2.5 bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-all shadow-xl" title="Mark as Read">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-all shadow-xl" title="Delete Permanent">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="h-24 w-24 rounded-full bg-slate-800 flex items-center justify-center text-slate-700 border border-slate-700 shadow-inner">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-300">Inbox Clear</h3>
                                    <p class="text-slate-500 max-w-xs font-medium">No system messages found in the database. You're all caught up!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($messages->hasPages())
            <div class="px-8 py-8 bg-slate-800/50 border-t border-slate-700 flex justify-center">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
