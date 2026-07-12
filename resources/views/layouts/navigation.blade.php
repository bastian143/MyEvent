<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-black " />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->hasRole('organizer') || Auth::user()->hasRole('admin'))
                            <x-nav-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')">
                                {{ __('Kelola Event') }}
                            </x-nav-link>
                        @endif

                        <x-nav-link :href="route('user.certificates.index')" :active="request()->routeIs('user.certificates.*')">
                            {{ __('Sertifikat') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('front.index')" :active="request()->routeIs('front.index')">
                            {{ __('Semua Event') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                    @auth
                        <!-- Notifications Dropdown -->
                        <div class="relative" x-data="{ notifyOpen: false }">
                            <button @click="notifyOpen = !notifyOpen" class="relative p-2 mr-2 text-slate-500 hover:text-brand-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full border-2 border-white">{{ $unreadCount }}</span>
                                @endif
                            </button>
                            <div x-show="notifyOpen" @click.away="notifyOpen = false" class="absolute right-0 mt-2 w-80 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50" style="display: none;">
                                <div class="px-4 py-2 border-b border-gray-100 font-bold text-black">Notifications</div>
                                <div class="max-h-64 overflow-y-auto">
                                    @php $notifications = Auth::user()->notifications()->take(5)->get(); @endphp
                                    @forelse($notifications as $notif)
                                        <a href="{{ route('notifications.read', $notif->id) }}" class="block px-4 py-3 hover:bg-slate-50 border-b border-gray-50 {{ $notif->read_at ? 'opacity-70' : 'bg-brand-50/30' }}">
                                            <p class="text-sm font-bold text-black">{{ $notif->data['title'] ?? 'Notification' }}</p>
                                            <p class="text-xs text-black mt-1">{{ $notif->data['message'] ?? '' }}</p>
                                        </a>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-black text-center">No notifications</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-gray-100 text-sm leading-4 font-medium rounded-md text-black  bg-white border border-gray-100 hover:text-black  focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Role Request Modal Trigger -->
                                @if(!Auth::user()->hasRole('admin'))
                                    <button @click="$dispatch('open-role-modal')" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        {{ Auth::user()->hasRole('organizer') ? 'Downgrade to User' : 'Request Organizer Role' }}
                                    </button>
                                @endif

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
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 font-bold hover">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 transition-colors">Register</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-brand-500  hover:text-black  hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-black  transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->hasRole('organizer') || Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')">
                        {{ __('Kelola Event') }}
                    </x-responsive-nav-link>
                @endif

                    <x-responsive-nav-link :href="route('user.certificates.index')" :active="request()->routeIs('user.certificates.*')">
                        {{ __('Sertifikat') }}
                    </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('front.index')" :active="request()->routeIs('front.index')">
                    {{ __('Semua Event') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 ">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-black ">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-black">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>

    <!-- Role Request Modal -->
    <div x-data="{ roleModalOpen: false }" @open-role-modal.window="roleModalOpen = true">
        <div x-show="roleModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div @click.away="roleModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-black">
                        @auth
                            {{ Auth::user()->hasRole('organizer') ? 'Downgrade Role' : 'Request Organizer Role' }}
                        @endauth
                    </h3>
                    <button @click="roleModalOpen = false" class="text-gray-400 hover:text-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('user.organizer.request') }}" method="POST" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Reason</label>
                        <textarea name="reason" rows="4" required class="w-full bg-slate-50 border border-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none" placeholder="Please state your reason for this request..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="roleModalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-lg hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</nav>
