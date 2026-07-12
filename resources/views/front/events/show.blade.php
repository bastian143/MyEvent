<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-brand-500 leading-tight">
                {{ $event->title }}
            </h2>
            <a href="{{ route('front.index') }}#events-section" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl border border-slate-200 transition-colors">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <!-- Cover Image -->
                <div class="w-full bg-slate-50 relative flex items-center justify-center p-4 rounded-t-xl border-b border-gray-100">
                    @if($event->poster)
                        <img src="{{ Storage::url($event->poster) }}" alt="{{ $event->title }}" class="w-full max-h-[70vh] object-contain rounded-lg shadow-sm">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-black bg-gradient-to-br from-surface_light to-surface">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-lg">Event Poster Available Soon</span>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 flex gap-2">
                        <span class="px-4 py-1.5 bg-black/60 backdrop-blur-md rounded-full text-sm font-bold text-white border border-gray-100">
                            {{ ucfirst($event->event_type) }}
                        </span>
                    </div>
                </div>

                <!-- Event Info -->
                <div class="p-6 sm">
                    <div class="grid grid-cols-1 lg gap-10">
                        <!-- Left Column: Details -->
                        <div class="lg space-y-8">
                            
                            @if(isset($pendingInvites) && $pendingInvites->count() > 0)
                                <div class="bg-white border border-amber-500/30 rounded-2xl p-6 shadow-md bg-amber-500/5">
                                    <h3 class="text-xl font-bold mb-4 text-amber-400 flex items-center gap-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                        Pending Team Invitations
                                    </h3>
                                    <div class="space-y-4">
                                        @foreach($pendingInvites as $invite)
                                            <div class="bg-slate-50 rounded-xl p-4 border-2 border-amber-500/20 flex flex-col sm justify-between items-start sm gap-4">
                                                <div>
                                                    <h4 class="font-bold text-lg text-white">Team: {{ $invite->team?->name }}</h4>
                                                    <p class="text-xs text-black mt-1">Invited by: {{ $invite->team->leader?->name }}</p>
                                                </div>
                                                <div class="flex gap-2">
                                                    <form action="{{ route('user.teams.respondInvite', $invite->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="action" value="accept">
                                                        <button type="submit" class="px-4 py-2 bg-emerald-500 hover text-white font-bold rounded-lg shadow-lg transition-colors text-sm">
                                                            Accept
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('user.teams.respondInvite', $invite->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="action" value="decline">
                                                        <button type="submit" class="px-4 py-2 bg-white hover/10 border border-gray-100 text-brand-500 font-bold rounded-lg transition-colors text-sm">
                                                            Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-2xl font-bold mb-4 text-brand-300">About the Event</h3>
                                <div class="prose prose-invert max-w-none text-brand-500 leading-relaxed">
                                    {!! nl2br(e($event->description)) !!}
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-gray-100">
                                <div>
                                    <span class="block text-sm text-black mb-1">Date & Time</span>
                                    <span class="font-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H') }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm text-black mb-1">Location</span>
                                    <span class="font-bold">{{ $event->location ?? 'To be announced' }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm text-black mb-1">Category</span>
                                    <span class="font-bold">{{ $event->category?->name ?? 'Uncategorized' }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm text-black mb-1">Contact</span>
                                    <span class="font-bold">{{ $event->contact_person }} ({{ $event->contact_phone }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Registration Card -->
                        <div class="lg">
                            <div class="bg-slate-50 rounded-2xl p-6 border border-gray-100 sticky top-24 shadow-2xl">
                                <div class="text-center mb-6">
                                    <span class="block text-sm text-black mb-1">Registration Fee</span>
                                    <span class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">
                                        {{ $event->price == 0 ? 'Free' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-black">Type</span>
                                        <span class="font-bold">{{ ucfirst($event->registration_type) }} Registration</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-black">Deadline</span>
                                        <span class="font-bold">{{ $event->registration_deadline ? \Carbon\Carbon::parse($event->registration_deadline)->format('d M Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-black">Quota Available</span>
                                        <span class="font-bold">{{ $event->quota > 0 ? $event->quota . ' seats' : 'Unlimited' }}</span>
                                    </div>
                                </div>

                                <hr class="border-gray-100 my-6">

                                @if(Auth::user())
                                    <a href="{{ route('user.events.register.create', $event->id) }}" class="w-full inline-block text-center py-3 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/20 transition-transform hover:scale-[1.02] active:scale-[0.98]">
                                        Register Now
                                    </a>

                                    @if($event->registration_type === 'team')
                                        <a href="{{ route('user.teams.index', $event->id) }}" class="mt-4 w-full block text-center py-3 bg-white/70 backdrop-blur-sm border border-gray-100 hover:bg-white text-brand-500 font-bold rounded-xl transition-colors shadow-sm">
                                            Find or Create Team
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="w-full inline-block text-center py-3 bg-white/70 backdrop-blur-sm border border-brand-500 hover:bg-brand-50 text-brand-500 font-bold rounded-xl transition-colors shadow-sm">
                                        Login to Register
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
