<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-2xl font-black text-[#1d5c42] tracking-tighter hover:opacity-80 transition">
                        Mazraa<span class="text-[#b46146]">.com</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">
                    <x-nav-link :href="url('/')" :active="request()->is('/')" class="font-bold text-sm">Home</x-nav-link>
                    <x-nav-link :href="route('explore')" :active="request()->routeIs('explore')" class="font-bold text-sm">Explore Farms</x-nav-link>
                    <x-nav-link href="/contact" :active="request()->is('contact')" class="font-bold text-sm">Contact</x-nav-link>
                    <x-nav-link href="/about" :active="request()->is('about')" class="font-bold text-sm">About</x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
                @guest
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-bold text-gray-600 hover:text-[#1d5c42] transition">
                                <div>Register</div>
                                <svg class="ml-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('register')" class="font-bold">New Customer</x-dropdown-link>
                            <x-dropdown-link :href="route('partner.register')" class="font-black text-[#1d5c42] border-t">List Your Farm</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-bold text-gray-600 hover:text-[#1d5c42] transition">
                                <div>Log in</div>
                                <svg class="ml-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('login')" class="font-bold">Customer Login</x-dropdown-link>
                            <div class="border-t border-gray-100 my-1"></div>
                            <x-dropdown-link :href="route('portal.login')" class="text-xs font-bold text-[#1d5c42]">Owner</x-dropdown-link>
                            <x-dropdown-link :href="route('supply-company.login')" class="text-xs font-bold">Supply Company</x-dropdown-link>
                            <x-dropdown-link :href="route('transport-company.login')" class="text-xs font-bold">Transport Company</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    <a href="{{ route('partner.register') }}" class="ml-2 px-5 py-2.5 bg-[#1d5c42] hover:bg-[#154230] text-white text-xs font-black uppercase tracking-widest rounded-full transition-transform active:scale-95 shadow-md">
                        List Your Farm
                    </a>
                @endguest

                @auth
                    <x-notification-bell />
                    <a href="{{ Auth::user()->role === 'user' ? route('dashboard') : url(match(Auth::user()->role){'admin'=>'/admin','farm_owner'=>'/owner/dashboard','supply_company'=>'/supplies/dashboard','transport_company'=>'/transport/dashboard','supply_driver'=>'/driver/supply/dashboard','transport_driver'=>'/driver/transport/dashboard',default=>'/'}) }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-[#1d5c42] transition">
                        My Dashboard
                    </a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-bold rounded-md text-[#1d5c42] bg-green-50 hover:bg-green-100 transition">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ml-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = true" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-[#1d5c42] hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" class="fixed inset-0 z-[100] sm:hidden" x-cloak>
        <div x-show="open"
             x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

        <div x-show="open"
             x-transition:enter="transform transition ease-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="fixed right-0 top-0 h-full w-[85%] max-w-sm bg-white shadow-2xl flex flex-col">

            <div class="flex items-center justify-between px-6 py-6 border-b border-slate-100">
                <a href="{{ url('/') }}" class="text-2xl font-black text-[#1d5c42] tracking-tighter">
                    Mazraa<span class="text-[#b46146]">.com</span>
                </a>
                <button @click="open = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto py-6 space-y-8">
                <section class="px-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Navigation</h3>
                    <div class="flex flex-col space-y-4">
                        <a href="{{ url('/') }}" class="text-lg font-bold text-slate-900 hover:text-[#1d5c42]">Home</a>
                        <a href="{{ route('explore') }}" class="text-lg font-bold text-slate-900 hover:text-[#1d5c42]">Explore Farms</a>
                        <a href="/about" class="text-lg font-bold text-slate-900 hover:text-[#1d5c42]">About Us</a>
                        <a href="/contact" class="text-lg font-bold text-slate-900 hover:text-[#1d5c42]">Contact</a>
                    </div>
                </section>

                <section class="bg-green-50/50 px-6 py-8 border-y border-green-100">
                    <h3 class="text-[10px] font-black text-[#1d5c42] uppercase tracking-widest mb-4">For Business Partners</h3>
                    <div class="space-y-4">
                        <a href="{{ route('partner.register') }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-[#1d5c42] text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                            List Your Farm
                        </a>
                        <div class="grid grid-cols-1 gap-3 pt-2">
                            <a href="{{ route('portal.login') }}" class="text-sm font-bold text-[#1d5c42] italic">Owner / Admin Login</a>
                            <div class="h-[1px] bg-green-200/50"></div>
                            <a href="{{ route('supply-company.login') }}" class="text-sm font-bold text-slate-600">Supply Company</a>
                            <a href="{{ route('transport-company.login') }}" class="text-sm font-bold text-slate-600">Transport Company</a>
                        </div>
                    </div>
                </section>

                <section class="px-6 pb-12">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Account</h3>
                    @auth
                        <div class="flex items-center gap-3 mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="h-10 w-10 rounded-full bg-[#1d5c42] flex items-center justify-center text-white font-black">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="overflow-hidden text-sm">
                                <p class="font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-4">
                            <a href="{{ Auth::user()->role === 'user' ? route('dashboard') : url(match(Auth::user()->role){'admin'=>'/admin','farm_owner'=>'/owner/dashboard','supply_company'=>'/supplies/dashboard','transport_company'=>'/transport/dashboard','supply_driver'=>'/driver/supply/dashboard','transport_driver'=>'/driver/transport/dashboard',default=>'/'}) }}" class="text-lg font-bold text-slate-900">My Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="text-lg font-bold text-slate-600">Profile Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                                @csrf
                                <button type="submit" class="text-lg font-black text-red-500">Log Out</button>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('login') }}" class="w-full py-4 text-center bg-slate-900 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-xl">Log In</a>
                            <a href="{{ route('register') }}" class="w-full py-4 text-center border-2 border-slate-900 text-slate-900 rounded-xl font-black text-xs uppercase tracking-widest">Sign Up</a>
                        </div>
                    @endauth
                </section>
            </div>
        </div>
    </div>
</nav>
