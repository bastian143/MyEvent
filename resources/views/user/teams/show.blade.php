<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            Team Info: {{ $team->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Team Info Card -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 border-2 border-brand-500/30 rounded-2xl">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-3xl font-extrabold text-white mb-2">{{ $team->name }}</h3>
                        <p class="text-black">Event: <a href="{{ route('front.events.show', $team->event->slug) }}" class="text-brand-400 hover font-semibold">{{ $team->event->title }}</a></p>
                    </div>
                    <div>
                        <span class="px-4 py-2 bg-emerald-500/20 text-emerald-400 border-2 border-emerald-500/30 rounded-full font-bold">
                            {{ $team->members->count() }} / {{ $team->event->team_size ?: 'Unlimited' }} Members
                        </span>
                    </div>
                </div>
            </div>

            <!-- Members List -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 rounded-2xl">
                <h4 class="text-xl font-bold mb-6 text-brand-300">Team Members</h4>
                <div class="grid gap-4">
                    @foreach($team->members as $member)
                        <div class="bg-slate-50 rounded-xl p-4 border border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-brand-500/20 flex items-center justify-center text-brand-300 font-bold text-xl">
                                    {{ substr($member->user?->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-bold text-white text-lg">{{ $member->user?->name }}</h5>
                                    <p class="text-xs text-black">{{ $member->user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                @if($member->user_id === $team->leader_id)
                                    <span class="px-3 py-1 bg-amber-500/20 text-amber-400 text-xs font-bold rounded-full border-2 border-amber-500/30">Leader</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-500/20 text-black text-xs font-bold rounded-full border-2 border-slate-500/30">Member</span>
                                @endif

                                @if($isLeader && $member->user_id !== Auth::user())
                                    <form action="{{ route('user.teams.kick', [$team->id, $member->user_id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to kick this member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-500/10 hover/20 text-red-400 rounded-lg transition-colors" title="Kick Member">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pending Join Requests (For Leader) -->
            @if($isLeader && isset($pendingRequests) && $pendingRequests->count() > 0)
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 border-2 border-brand-500/30 rounded-2xl">
                    <h4 class="text-xl font-bold mb-6 text-brand-300">Pending Join Requests</h4>
                    <div class="grid gap-4">
                        @foreach($pendingRequests as $req)
                            <div class="bg-slate-50 rounded-xl p-4 border-2 border-brand-500/20 flex flex-col sm justify-between items-start sm gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-500/20 flex items-center justify-center text-brand-500 font-bold text-lg">
                                        {{ substr($req->user?->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-white">{{ $req->user?->name }}</h5>
                                        <p class="text-xs text-black">{{ $req->user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('user.teams.respondRequest', $req->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="px-4 py-2 bg-emerald-500 hover text-white font-bold rounded-lg shadow-lg transition-colors text-sm">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('user.teams.respondRequest', $req->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="decline">
                                        <button type="submit" class="px-4 py-2 bg-red-500/20 hover/40 border-2 border-red-500/30 text-red-300 font-bold rounded-lg transition-colors text-sm">
                                            Deny
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Management Actions -->
            @if($isMember)
                <div class="flex justify-end gap-4 mt-8">
                    @if($isLeader)
                        <form action="{{ route('user.teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to disband and delete this team? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-6 py-3 bg-red-600 hover text-white font-bold rounded-xl shadow-lg transition-transform hover">
                                Disband Team
                            </button>
                        </form>
                    @else
                        <form action="{{ route('user.teams.leave', $team->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this team?');">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-red-500/20 hover/40 border-2 border-red-500/30 text-red-400 font-bold rounded-xl transition-colors">
                                Leave Team
                            </button>
                        </form>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
