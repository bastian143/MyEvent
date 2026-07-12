<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-black leading-tight">
                {{ __('My Events (Organizer)') }}
            </h2>
            <a href="{{ route('organizer.events.create') }}" class="px-5 py-2.5 bg-brand-500 hover text-white font-bold rounded-full shadow-lg transition-transform hover">
                + Create New Event
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
                                <th class="p-4 font-semibold">Event Title</th>
                                <th class="p-4 font-semibold">Date</th>
                                <th class="p-4 font-semibold">Type</th>
                                <th class="p-4 font-semibold">Status</th>
                                <th class="p-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($events as $event)
                                <tr class="hover/5 transition-colors">
                                    <td class="p-4">
                                        <p class="font-bold line-clamp-1 text-black">{{ $event->title }}</p>
                                    </td>
                                    <td class="p-4 text-brand-500">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</td>
                                    <td class="p-4 text-brand-500">{{ ucfirst($event->event_type) }}</td>
                                    <td class="p-4">
                                        @if($event->status === 'approved')
                                            <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-bold rounded">Approved</span>
                                        @elseif($event->status === 'pending')
                                            <span class="px-2 py-1 bg-amber-500/20 text-amber-400 text-xs font-bold rounded">Pending</span>
                                        @elseif($event->status === 'rejected')
                                            <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs font-bold rounded" title="{{ $event->rejection_reason }}">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right space-x-2">
                                        <a href="{{ route('organizer.events.fields.index', $event->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-gray-100 text-xs font-bold rounded-lg transition-colors">
                                            Form Builder
                                        </a>
                                        @if($event->status === 'approved')
                                            <a href="{{ route('organizer.events.registrations.index', $event->id) }}" class="px-3 py-1 bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 text-xs font-bold rounded-lg transition-colors">
                                                Participants
                                            </a>
                                            <a href="{{ route('organizer.events.certificates.index', $event->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-gray-100 text-xs font-bold rounded-lg transition-colors">
                                                Certificates
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-black">You haven't created any events yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
