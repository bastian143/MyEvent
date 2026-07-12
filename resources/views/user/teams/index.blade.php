<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-brand-500 leading-tight">
            Teams for: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-500/20 border-2 border-emerald-500/30 text-emerald-300 font-semibold">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 rounded-xl bg-red-500/20 border-2 border-red-500/30 text-red-300 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Create Team Card -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 border-2 border-brand-500/30 rounded-2xl">
                <h3 class="text-xl font-bold mb-4">Create Your Own Team</h3>
                <form action="{{ route('user.teams.store', $event->id) }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Team Name</label>
                        <input type="text" name="name" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-gray-900 focus outline-none">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl shadow-md transition-transform">
                        Create Team
                    </button>
                </form>
            </div>

            <!-- Existing Teams -->
            <div>
                <h3 class="text-2xl font-bold mb-6">Available Teams to Join</h3>
                
                <div class="grid grid-cols-1 md lg gap-6">
                    @forelse($teams as $team)
                        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 border border-gray-100 rounded-2xl flex flex-col h-full">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900">{{ $team->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">Leader: {{ $team->leader?->name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 bg-white/10 text-brand-500 text-xs font-bold rounded block mb-1">
                                        {{ ucfirst($team->status) }}
                                    </span>
                                    @if($event->team_size)
                                        <span class="text-xs font-bold text-brand-300">
                                            {{ $team->members()->where('status', 'joined')->count() }} / {{ $event->team_size }} Members
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-6 border-t border-gray-100 space-y-4">
                                @if($team->members()->where('user_id', Auth::user())->exists())
                                    <a href="{{ route('user.teams.show', $team->id) }}" class="w-full inline-block text-center py-2 bg-brand-500/20 text-brand-300 font-bold rounded-lg border-2 border-brand-500/30 hover/30 transition-colors">
                                        Manage / View Team
                                    </a>
                                @endif

                                @if($team->leader_id === Auth::user())
                                    <form action="{{ route('user.teams.invite', $team->id) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input type="email" name="email" placeholder="Invite by Email" required class="w-full bg-slate-50 border border-gray-100 rounded-lg px-3 py-2 text-sm text-gray-900 focus outline-none">
                                            <button type="submit" class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-lg transition-colors text-sm whitespace-nowrap">
                                                Invite
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    @if(!$team->members()->where('user_id', Auth::user())->exists())
                                        @php
                                            $isFull = $event->team_size && $team->members()->where('status', 'joined')->count() >= $event->team_size;
                                        @endphp
                                        @if($isFull)
                                            <button disabled class="w-full py-2.5 bg-red-500/20 border-2 border-red-500/50 text-red-400 font-bold rounded-xl cursor-not-allowed">
                                                Team Full
                                            </button>
                                        @else
                                            <form action="{{ route('user.teams.join', $team->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full py-2.5 bg-brand-50 border border-brand-200 hover:bg-brand-100 text-brand-600 font-bold rounded-xl transition-all">
                                                    Request to Join
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-white border border-gray-100 rounded-xl shadow-sm border border-gray-100 rounded-2xl">
                            <p class="text-black">No teams have been created yet. Be the first to create one!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 flex justify-start">
                <a href="{{ route('front.events.show', $event->slug) }}" class="text-brand-400 hover font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Event
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
