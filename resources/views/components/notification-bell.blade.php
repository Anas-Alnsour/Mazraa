<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    {{-- Bell Icon Trigger --}}
    <button @click="open = ! open" class="relative p-2.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition-all focus:outline-none rounded-full group">
        <svg class="w-6 h-6 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        @auth
            @php
                $unreadCount = auth()->user()->unreadNotifications->count();
            @endphp
            
            @if($unreadCount > 0)
                <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-600 border-2 border-white"></span>
                </span>
            @endif
        @endauth
    </button>

    {{-- Dropdown Body --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.12)] overflow-hidden z-[100] border border-slate-100"
         style="display: none;">
        
        @auth
            @php $unreadNotifications = auth()->user()->unreadNotifications->take(5); @endphp
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                    Notifications
                    @if($unreadCount > 0)
                        <span class="bg-emerald-600 text-white px-2 py-0.5 rounded-md text-[10px] shadow-sm shadow-emerald-600/20">{{ $unreadCount }}</span>
                    @endif
                </h3>
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.read_all') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[10px] font-black text-slate-400 hover:text-emerald-600 uppercase tracking-widest transition-colors">
                            Clear All
                        </button>
                    </form>
                @endif
            </div>

            <div class="max-h-[450px] overflow-y-auto overscroll-contain scrollbar-hide">
                @forelse(auth()->user()->notifications->take(10) as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}" 
                       class="block px-6 py-5 hover:bg-slate-50 transition-all border-b border-slate-50 group {{ $notification->unread() ? 'bg-emerald-50/40 border-l-4 border-l-emerald-500' : 'bg-white' }}">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                @if($notification->unread())
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                @endif
                                <p class="text-sm font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">
                                    {{ $notification->data['title'] ?? 'System Update' }}
                                </p>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap ml-3 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $notification->created_at->diffForHumans(null, true, true) }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium line-clamp-2 leading-relaxed group-hover:text-slate-700 transition-colors">
                            {{ $notification->data['message'] ?? 'Click to view details.' }}
                        </p>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center bg-white">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100 shadow-inner">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-1">All Caught Up!</h4>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-tighter">No new notifications at this time</p>
                    </div>
                @endforelse
            </div>

            @php
                $inboxRoute = match(auth()->user()->role) {
                    'super_admin', 'admin' => route('admin.dashboard'),
                    'farm_owner' => route('owner.dashboard'),
                    'supply_company' => route('supplies.dashboard'),
                    'transport_company' => route('transport.dashboard'),
                    'supply_driver' => route('supply.driver.dashboard'),
                    'transport_driver' => route('transport.driver.dashboard'),
                    'user' => route('dashboard'),
                    default => route('dashboard'),
                };
            @endphp
            <div class="p-4 border-t border-slate-50 bg-slate-50/30">
                <a href="{{ $inboxRoute }}" class="block w-full py-3 text-center text-xs font-black text-slate-400 hover:text-slate-900 uppercase tracking-widest transition-all">
                    View Full Inbox
                </a>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-900">Authentication Required</p>
                <p class="text-xs text-slate-500 mt-1 mb-6">Please log in to view your notifications.</p>
                <a href="{{ route('login') }}" class="inline-block px-10 py-3 bg-slate-900 text-white text-xs font-black rounded-xl uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg hover:shadow-emerald-600/20">Login</a>
            </div>
        @endauth
    </div>
</div>
