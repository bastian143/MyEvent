<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-y-scroll">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyEvent - Platform Event Terbaik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-black bg-white selection:bg-brand-500 selection:text-white">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <x-application-logo class="block h-12 w-auto" />
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm px-4 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 font-bold hover:text-brand-500 transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 transition-colors">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main>
        <div class="relative overflow-hidden bg-gradient-to-br from-brand-500 via-orange-400 to-yellow-400 border-b border-brand-600 min-h-[calc(100vh-80px)] flex flex-col justify-center">
            
            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-20 w-full text-white">
                <div class="text-center max-w-5xl mx-auto">
                    <h1 class="text-5xl md:text-7xl font-bold font-serif mb-8 leading-tight tracking-tight drop-shadow-md">
                        Temukan <span class="text-white inline-block transform hover:rotate-2 transition-transform cursor-pointer border-b-4 border-white">Event & Lomba</span> Terbaikmu!
                    </h1>
                    <p class="text-xl md:text-2xl font-serif mb-12 border-x-4 border-white px-8 inline-block bg-black/10 border-white/20 py-3 rounded-2xl shadow-sm text-white backdrop-blur-sm">
                        Ikuti ratusan event kompetisi, seminar, dan workshop.
                    </p>

                    <!-- Search Form -->
                    <form action="{{ route('front.index') }}#events-section" method="GET" class="flex flex-col sm:flex-row gap-4 max-w-4xl mx-auto justify-center w-full">
                        <div class="flex-1 relative group w-full">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik 'Lomba Essay' atau 'Seminar IT'..." class="w-full pl-14 pr-6 py-5 bg-white border-2 border-gray-100 rounded-2xl font-bold text-lg focus:ring-0 focus:border-brand-500 shadow-sm outline-none transition-all hover:shadow-md">
                        </div>
                        <button type="submit" class="px-12 py-5 bg-black hover:bg-brand-500 text-white font-bold text-lg border-2 border-transparent rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all whitespace-nowrap">
                            Cari Event
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories & Events Section -->
        <div id="events-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
            
            <!-- Filter Categories -->
            <div class="flex overflow-x-auto py-4 gap-3 no-scrollbar mb-10 pb-4 border-b border-gray-100">
                <a href="{{ route('front.index') }}#events-section" class="shrink-0 px-6 py-2 rounded-xl font-bold border border-gray-100 transition-all {{ !request('category_id') ? 'bg-brand-500 text-white shadow-sm' : 'bg-white hover:bg-brand-50' }}">
                    Semua Event
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('front.index', ['category_id' => $cat->id]) }}#events-section" class="shrink-0 px-6 py-2 rounded-xl font-bold border border-gray-100 transition-all {{ request('category_id') == $cat->id ? 'bg-brand-500 text-white shadow-sm' : 'bg-white hover:bg-brand-50' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Event Grid -->
            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md hover:shadow-md hover:-translate-y-1 hover:translate-x-1 hover:translate-y-1 transition-all flex flex-col group cursor-pointer" onclick="window.location.href='{{ route('front.events.show', $event->slug) }}'">
                            
                            <!-- Banner Image -->
                            <div class="aspect-[4/5] bg-slate-100 border-b border-gray-100 relative overflow-hidden flex items-center justify-center">
                                @if($event->poster)
                                    <!-- Main Thumbnail Image -->
                                    <img src="{{ Storage::url($event->poster) }}" alt="{{ $event->title }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-brand-50 text-brand-500">
                                        <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-brand-500 text-white font-bold px-3 py-1 text-xs rounded-full border border-gray-100">
                                    {{ $event->category?->name ?? 'Event' }}
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="font-bold text-xl mb-3 line-clamp-2 group-hover transition-colors">{{ $event->title }}</h3>
                                
                                <div class="space-y-2 mt-auto pt-4 border-t border-gray-100 border-dashed">
                                    <div class="flex items-center text-sm font-bold gap-2">
                                        <div class="w-8 h-8 rounded-full bg-brand-50 flex items-center justify-center border border-gray-100 shrink-0">
                                            <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <span>{{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm font-bold gap-2">
                                        <div class="w-8 h-8 rounded-full bg-brand-50 flex items-center justify-center border border-gray-100 shrink-0">
                                            <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <span class="line-clamp-1">{{ $event->location }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-12 flex justify-center">
                    {{ $events->appends(request()->query())->fragment('events-section')->links() }}
                </div>
            @else
                <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-md">
                    <div class="w-24 h-24 mx-auto bg-brand-50 rounded-full flex items-center justify-center border border-gray-100 mb-6 transform -rotate-6">
                        <svg class="w-12 h-12 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Oops! Event Tidak Ditemukan</h3>
                    <p class="font-medium text-lg">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12 border-t-8 border-brand-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <x-application-logo class="block h-16 w-auto mx-auto mb-6 grayscale brightness-0 invert" />
            <p class="font-bold text-lg mb-8">Platform andalan untuk lomba dan seminar.</p>
            <div class="border-t-2 border-white/20 pt-8 mt-8">
                <p class="font-bold">&copy; {{ date('Y') }} MyEvent. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
