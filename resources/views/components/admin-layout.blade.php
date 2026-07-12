<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MyEvent') }} - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900 flex min-h-screen relative overflow-hidden">
    <!-- Background Decor -->
    <div class="fixed top-0 left-0 w-[800px] h-[800px] bg-brand-500/10 blur-[150px] rounded-full pointer-events-none z-0 -translate-x-1/2 -translate-y-1/2"></div>
    <div class="fixed bottom-0 right-0 w-[600px] h-[600px] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none z-0 translate-x-1/3 translate-y-1/3"></div>

    <!-- Sidebar -->
    <aside class="w-64 flex-shrink-0 bg-white/70 backdrop-blur-xl border-r border-white/50 shadow-lg relative z-20 flex flex-col hidden md:flex">
        <div class="h-20 flex items-center px-6 border-b border-white/50">
            <a href="/" class="flex items-center gap-2">
                <x-application-logo class="block h-8 w-auto" />
                <span class="text-xl font-extrabold text-gradient">Admin</span>
            </a>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-500/20 text-brand-600 font-bold' : 'text-gray-700 hover:bg-white/50 hover' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.events.*') ? 'bg-brand-500/20 text-brand-600 font-bold' : 'text-gray-700 hover:bg-white/50 hover' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Events
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-brand-500/20 text-brand-600 font-bold' : 'text-gray-700 hover:bg-white/50 hover' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Users
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-brand-500/20 text-brand-600 font-bold' : 'text-gray-700 hover:bg-white/50 hover' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Categories
            </a>
            <a href="{{ route('admin.activity_logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.activity_logs.*') ? 'bg-brand-500/20 text-brand-600 font-bold' : 'text-gray-700 hover:bg-white/50 hover' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Activity Logs
            </a>
        </nav>

        <div class="p-4 border-t border-white/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-500/10 transition-all font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 relative z-10 flex flex-col h-screen overflow-hidden">
        <!-- Topbar -->
        <!-- Topbar -->
        <header class="h-20 bg-white/70 backdrop-blur-xl border-b border-white/50 shadow-sm flex items-center justify-between px-8 flex-shrink-0 relative z-20">
            <h1 class="text-xl font-bold">{{ $title ?? 'Admin Area' }}</h1>
            <div class="flex items-center gap-4">
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

                <span class="text-sm font-semibold text-gray-700">Welcome, {{ Auth::user()->name }}</span>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border-2 border-emerald-500/30 text-emerald-300 font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/20 border-2 border-red-500/30 text-red-300 font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>
</body>
</html>
