<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-black leading-tight">
                {{ __('Participant Management: ') }} {{ $event->title }}
            </h2>
            <a href="{{ route('organizer.events.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl border border-gray-200 transition-colors">
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-500/20 border-2 border-emerald-500/30 text-emerald-300 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                                <th class="p-4 font-semibold">Participant</th>
                                <th class="p-4 font-semibold">Form Answers</th>
                                <th class="p-4 font-semibold">Status</th>
                                <th class="p-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($registrations as $registration)
                                <tr class="hover/5 transition-colors">
                                    <td class="p-4">
                                        <p class="font-bold text-black">{{ $registration->user?->name }}</p>
                                        <p class="text-xs text-black">{{ $registration->user->email }}</p>
                                        <p class="text-xs text-black mt-1">Registered: {{ $registration->created_at->format('d M Y, H') }}</p>
                                    </td>
                                    <td class="p-4">
                                        @if($registration->answers->count() > 0)
                                            <div class="space-y-2 max-w-sm">
                                                @foreach($registration->answers as $answer)
                                                    <div class="bg-slate-50 p-2 rounded border border-gray-100">
                                                        <span class="block text-xs font-bold text-brand-300">{{ $answer->field->name }}</span>
                                                        @if($answer->field->type === 'file' && $answer->value)
                                                            <a href="{{ Storage::url($answer->value) }}" target="_blank" class="text-xs text-blue-400 hover flex items-center gap-1 mt-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                View File
                                                            </a>
                                                        @else
                                                            <span class="text-sm text-slate-800">{{ $answer->value ?? '-' }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-black text-sm italic">No custom fields for this event.</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        @if($registration->status === 'approved')
                                            <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-bold rounded">Approved</span>
                                        @elseif($registration->status === 'pending')
                                            <span class="px-2 py-1 bg-amber-500/20 text-amber-400 text-xs font-bold rounded">Pending</span>
                                        @elseif($registration->status === 'rejected')
                                            <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs font-bold rounded">Rejected</span>
                                        @else
                                            <span class="px-2 py-1 bg-white/10 text-black text-xs font-bold rounded">{{ ucfirst($registration->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('organizer.events.registrations.status', [$event->id, $registration->id]) }}" method="POST" class="flex flex-col gap-2 items-end">
                                            @csrf
                                            <div class="flex gap-2">
                                                <button type="submit" name="status" value="approved" class="px-3 py-1 bg-emerald-500/20 hover/40 text-emerald-400 border-2 border-emerald-500/30 text-xs font-bold rounded transition-colors">
                                                    Approve
                                                </button>
                                                <button type="submit" name="status" value="rejected" class="px-3 py-1 bg-red-500/20 hover/40 text-red-400 border-2 border-red-500/30 text-xs font-bold rounded transition-colors" onclick="return confirm('Reject this participant?')">
                                                    Reject
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-black">No participants have registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
