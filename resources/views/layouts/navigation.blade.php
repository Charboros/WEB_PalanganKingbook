<nav x-data="{ open: false }" class="bg-green-700 border-b border-green-800 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="h-9 w-9 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="h-5 w-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-extrabold text-white tracking-tight">SportBook</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-green-100 hover:text-white focus:text-white transition duration-150 ease-in-out">
                        {{ __('Katalog Lapangan') }}
                    </x-nav-link>
                    
                    @auth
                        @if(auth()->user()->role === 'user')
                        <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" class="text-green-100 hover:text-white focus:text-white transition duration-150 ease-in-out">
                            {{ __('Booking Saya') }}
                        </x-nav-link>
                        <x-nav-link :href="route('user.membership')" :active="request()->routeIs('user.membership')" class="text-green-100 hover:text-white focus:text-white transition duration-150 ease-in-out">
                            {{ __('Membership') }}
                        </x-nav-link>
                        @endif
                        
                        @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-green-100 hover:text-white focus:text-white transition duration-150 ease-in-out">
                            {{ __('Admin Panel') }}
                        </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-green-600 text-sm leading-4 font-semibold rounded-xl text-white bg-green-800 hover:bg-green-900 focus:outline-none transition ease-in-out duration-150">
                            <div class="h-7 w-7 rounded-full bg-white flex items-center justify-center text-green-800 text-xs font-bold shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-green-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-green-100 hover:text-white transition">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-bold text-green-800 bg-white hover:bg-green-50 px-5 py-2 rounded-xl transition shadow-sm">Register</a>
                    @endif
                </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-green-200 hover:text-white hover:bg-green-800 focus:outline-none focus:bg-green-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-green-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-white hover:bg-green-900 focus:bg-green-900">
                {{ __('Katalog Lapangan') }}
            </x-responsive-nav-link>
            
            @auth
                @if(auth()->user()->role === 'user')
                <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" class="text-white hover:bg-green-900 focus:bg-green-900">
                    {{ __('Booking Saya') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.membership')" :active="request()->routeIs('user.membership')" class="text-white hover:bg-green-900 focus:bg-green-900">
                    {{ __('Membership') }}
                </x-responsive-nav-link>
                @endif
                
                @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-white hover:bg-green-900 focus:bg-green-900">
                    {{ __('Admin Panel') }}
                </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-green-700">
            @auth
            <div class="px-4 flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-white flex items-center justify-center text-green-800 text-sm font-bold shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-green-200">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-green-900 focus:bg-green-900">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-white hover:bg-green-900 focus:bg-green-900">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('login')" class="text-white hover:bg-green-900 focus:bg-green-900">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                <x-responsive-nav-link :href="route('register')" class="text-white hover:bg-green-900 focus:bg-green-900">
                    {{ __('Register') }}
                </x-responsive-nav-link>
                @endif
            </div>
            @endauth
        </div>
    </div>
</nav>
