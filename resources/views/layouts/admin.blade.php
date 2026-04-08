<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode | Mazraa Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-900 font-sans antialiased text-slate-200 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-slate-900/50 lg:hidden" @click="sidebarOpen = false"></div>

    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed inset-y-0 left-0 z-30 w-72 bg-slate-900 text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-2xl">
        <div class="flex items-center justify-center h-20 border-b border-slate-800">
            <h1 class="text-2xl font-black tracking-widest text-emerald-400 flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                MAZRAA <span class="text-white text-sm font-bold ml-1">ADMIN</span>
            </h1>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
            <p class="px-2 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Command Center</p>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold text-sm transition-all transform active:scale-95 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Financial Hub
            </a>

            @php $pendingCount = \App\Models\Farm::where('is_approved', false)->count(); @endphp
            <a href="{{ route('admin.verifications') }}" class="flex items-center justify-between px-4 py-4 rounded-2xl font-bold text-sm transition-all transform active:scale-95 {{ request()->routeIs('admin.verifications') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Verifications
                </div>
                @if($pendingCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-sm animate-pulse">{{ $pendingCount }} NEW</span>
                @endif
            </a>

            @php $unreadMessages = \App\Models\ContactMessage::where('status', 'unread')->count(); @endphp
            <a href="{{ route('admin.contacts.index') }}" class="flex items-center justify-between px-4 py-4 rounded-2xl font-bold text-sm transition-all transform active:scale-95 {{ request()->routeIs('admin.contacts.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Messages
                </div>
                @if($unreadMessages > 0)
                    <span class="bg-emerald-500 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-sm animate-pulse">{{ $unreadMessages }} NEW</span>
                @endif
            </a>

            <p class="px-2 text-[10px] font-black text-slate-500 uppercase tracking-widest mt-8 mb-4">Management</p>
            <a href="javascript:void(0);" class="flex items-center gap-3 px-4 py-4 text-slate-500 rounded-2xl font-bold text-sm transition-all opacity-50 cursor-not-allowed">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users & Roles
            </a>
<a href="{{ route('admin.payouts') }}" class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold text-sm transition-all transform active:scale-95 {{ request()->routeIs('admin.payouts') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Financial Payouts
            </a>

            <a href="{{ route('admin.financials') }}" class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold text-sm transition-all transform active:scale-95 {{ request()->routeIs('admin.financials') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Financial Reports
            </a>

            <a href="{{ route('admin.system') }}" class="flex items-center gap-3 px-4 py-4 rounded-2xl font-bold text-sm transition-all transform active:scale-95 {{ request()->routeIs('admin.system') ? 'bg-slate-600 text-white shadow-lg shadow-slate-700/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                System Management
            </a>
        </nav>


        <div class="px-6 py-6 border-t border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-xl transition-all font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Secure Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-900">
        <header class="h-20 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-6 lg:px-10 shadow-sm shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:text-emerald-500 focus:outline-none bg-slate-800 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-700 px-3 py-1.5 rounded-lg">God Mode Activated</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                
                <x-notification-bell />
                
                <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100 shadow-sm hidden sm:flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ date('l, M j, Y') }}
                </div>
                
                {{-- Admin Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <div @click="open = !open" class="h-10 w-10 rounded-full bg-slate-900 text-emerald-400 flex items-center justify-center font-black border-2 border-emerald-400 shadow-sm cursor-pointer hover:bg-emerald-400 hover:text-slate-900 transition-all">
                        SA
                    </div>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="transform opacity-0 scale-95" 
                         x-transition:enter-end="transform opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="transform opacity-100 scale-100" 
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-48 bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl py-2 z-50">
                        <div class="px-4 py-2 border-b border-slate-700 mb-2">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Admin</p>
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-300 hover:text-emerald-400 hover:bg-slate-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-500/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 lg:p-10 relative">
            {{-- Global Messages --}}
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 8000)" class="fixed top-24 right-5 z-[110] flex flex-col gap-3 pointer-events-none">
                @if (session('success'))
                    <div x-show="show" x-transition.duration.500ms class="pointer-events-auto bg-slate-800/90 backdrop-blur-xl border border-emerald-500/50 rounded-2xl shadow-2xl p-4 flex items-start gap-4 w-80 transform hover:scale-105 transition-all">
                        <div class="bg-emerald-500 p-2 rounded-full text-white flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                        <div class="flex-1"><h4 class="font-black text-white text-sm uppercase tracking-wider">Success</h4><p class="text-slate-400 text-xs mt-1 font-medium leading-relaxed">{{ session('success') }}</p></div>
                        <button @click="show = false" class="text-slate-500 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif
                @if (session('error'))
                    <div x-show="show" x-transition.duration.500ms class="pointer-events-auto bg-slate-800/90 backdrop-blur-xl border border-rose-500/50 rounded-2xl shadow-2xl p-4 flex items-start gap-4 w-80 transform hover:scale-105 transition-all">
                        <div class="bg-rose-500 p-2 rounded-full text-white flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                        <div class="flex-1"><h4 class="font-black text-white text-sm uppercase tracking-wider">Error</h4><p class="text-slate-400 text-xs mt-1 font-medium leading-relaxed">{{ session('error') }}</p></div>
                        <button @click="show = false" class="text-slate-500 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div x-show="show" x-transition.duration.500ms class="pointer-events-auto bg-slate-800/90 backdrop-blur-xl border border-amber-500/50 rounded-2xl shadow-2xl p-4 flex items-start gap-4 w-80 transform hover:scale-105 transition-all">
                        <div class="bg-amber-500 p-2 rounded-full text-slate-900 flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                        <div class="flex-1">
                            <h4 class="font-black text-white text-sm uppercase tracking-wider">Attention</h4>
                            <ul class="list-disc list-inside text-slate-400 text-[10px] mt-1 font-medium leading-relaxed">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-slate-500 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>
</body>
</html>

