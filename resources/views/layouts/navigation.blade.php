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
                    <x-nav-link :href="url('/')" :active="request()->is('/')" class="font-bold text-sm">
                        Home
                    </x-nav-link>
                    <x-nav-link :href="route('explore')" :active="request()->routeIs('explore')" class="font-bold text-sm">
                        Explore Farms
                    </x-nav-link>
                    <x-nav-link href="/contact" :active="request()->is('contact')" class="font-bold text-sm">
                        Contact
                    </x-nav-link>
                    <x-nav-link href="/about" :active="request()->is('about')" class="font-bold text-sm">
                        About
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
                @guest
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-bold rounded-md text-gray-600 hover:text-[#1d5c42] focus:outline-none transition">
                                <div>Register</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('register')" class="font-bold text-sm">New Customer</x-dropdown-link>
                            <x-dropdown-link :href="route('partner.register')" class="font-black text-[#1d5c42] border-t border-gray-100">List Your Farm</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-bold rounded-md text-gray-600 hover:text-[#1d5c42] focus:outline-none transition">
                                <div>Log in</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('login')" class="font-bold text-sm">Customer Login</x-dropdown-link>
                            <div class="border-t border-gray-100 my-1"></div>
                            <x-dropdown-link :href="route('portal.login')" class="text-xs font-bold text-gray-600">Admin / Farm Owner</x-dropdown-link>
                            <x-dropdown-link :href="route('portal.login')" class="text-xs font-bold text-gray-600">Supply Co. & Drivers</x-dropdown-link>
                            <x-dropdown-link :href="route('portal.login')" class="text-xs font-bold text-gray-600">Transport Co. & Drivers</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    <a href="{{ route('partner.register') }}" class="ml-2 px-5 py-2.5 bg-[#1d5c42] hover:bg-[#154230] text-white text-xs font-black uppercase tracking-widest rounded-full transition-transform active:scale-95 shadow-md">
                        List Your Farm
                    </a>
                @endguest

                @auth
                    <a href="{{ Auth::user()->role === 'user' ? route('dashboard') : url(match(Auth::user()->role){'admin'=>'/admin','farm_owner'=>'/owner/dashboard','supply_company'=>'/supplies/dashboard','transport_company'=>'/transport/dashboard','supply_driver'=>'/delivery/orders','transport_driver'=>'/shuttle/trips',default=>'/'}) }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-[#1d5c42] transition">
                        My Dashboard
                    </a>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-bold rounded-md text-[#1d5c42] bg-green-50 hover:bg-green-100 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>
        </div>
    </div>
</nav>
