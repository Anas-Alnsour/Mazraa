<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    {{-- Bell Icon Trigger --}}
    <button @click="open = ! open" class="relative p-2 text-gray-500 hover:text-gray-700 transition-colors focus:outline-none focus:text-gray-900 focus:bg-gray-100 rounded-full">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        
        @auth
            @php
                $unreadCount = auth()->user()->unreadNotifications->count();
            @endphp
            
            @if($unreadCount > 0)
                <span class="absolute top-1 right-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full border-2 border-white pointer-events-none">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        @endauth
    </button>

    {{-- Dropdown Body --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-[#020617] rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden z-[100] border border-gray-800"
         style="display: none;">
        
        @auth
            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
            <div class="px-5 py-4 border-b border-gray-800/80 bg-gradient-to-r from-[#020617] to-[#0f172a]">
                <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                    Notifications
                    @if($unreadCount > 0)
                        <span class="bg-[#1d5c42]/20 text-emerald-400 px-2 py-0.5 rounded-md text-[10px]">{{ $unreadCount }} New</span>
                    @endif
                </h3>
            </div>

            <div class="max-h-[60vh] overflow-y-auto overscroll-contain scrollbar-hide">
                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-5 py-4 hover:bg-white/5 transition-colors border-b border-gray-800/50 group">
                        <div class="flex justify-between items-start mb-1">
                            <p class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors line-clamp-1">
                                {{ $notification->data['title'] ?? 'System Notification' }}
                            </p>
                            <span class="text-[10px] font-medium text-gray-500 whitespace-nowrap ml-3">
                                {{ $notification->created_at->diffForHumans(null, true, true) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 font-medium line-clamp-2 leading-relaxed">
                            {{ $notification->data['message'] ?? 'You have a new message.' }}
                        </p>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center">
                        <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-500">All caught up!</p>
                        <p class="text-[10px] text-gray-600 uppercase tracking-widest mt-1">No new notifications</p>
                    </div>
                @endforelse
            </div>

            @if($unreadCount > 0)
                <div class="p-3 border-t border-gray-800/80 bg-[#020617]">
                    <form action="{{ route('notifications.read_all') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 text-xs font-black text-gray-300 hover:text-white uppercase tracking-widest hover:bg-white/5 rounded-2xl transition-colors text-center focus:outline-none">
                            Mark all as read
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="px-5 py-12 text-center text-gray-400">
                <p class="text-sm font-bold">Please log in to view notifications.</p>
                <a href="{{ route('login') }}" class="mt-4 inline-block text-xs font-black text-[#c2a265] uppercase tracking-widest hover:text-white transition-colors underline">Login Now</a>
            </div>
        @endauth
    </div>
</div>

