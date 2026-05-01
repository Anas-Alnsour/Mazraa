@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-end px-4 sm:px-0">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Inbox Notifications</h2>
                <p class="text-slate-500 font-medium mt-1">Manage all your system alerts and updates in one place.</p>
            </div>
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-white border-2 border-slate-200 text-slate-600 rounded-xl hover:border-emerald-500 hover:text-emerald-600 text-sm font-bold shadow-sm transition-all focus:outline-none">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="bg-white shadow-xl shadow-slate-200/40 rounded-[2rem] border border-slate-100 overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notification)
                    <div class="p-6 transition-all {{ $notification->unread() ? 'bg-emerald-50/30' : 'bg-white hover:bg-slate-50' }}">
                        <div class="flex items-start gap-4">
                            <!-- Status Icon -->
                            <div class="shrink-0 mt-1">
                                @if($notification->unread())
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center border border-emerald-200 shadow-inner">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center border border-slate-200">
                                        <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <h3 class="text-base font-bold {{ $notification->unread() ? 'text-slate-900' : 'text-slate-700' }}">
                                        {{ $notification->data['title'] ?? 'System Notification' }}
                                    </h3>
                                    <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap bg-slate-100 px-3 py-1 rounded-full uppercase tracking-widest w-fit">
                                        {{ $notification->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                                <p class="text-sm {{ $notification->unread() ? 'text-slate-600 font-semibold' : 'text-slate-500 font-medium' }} leading-relaxed mb-4">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                
                                <!-- Action Button -->
                                <a href="{{ route('notifications.redirect', $notification->id) }}" class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-widest {{ $notification->unread() ? 'text-emerald-600 hover:text-emerald-700' : 'text-slate-400 hover:text-slate-600' }} transition-colors">
                                    View Details
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-16 text-center">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-slate-100 border-dashed">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 mb-2">Inbox is Empty</h3>
                        <p class="text-slate-500 font-medium">You don't have any notifications at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection