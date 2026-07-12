<x-admin-layout>
    <x-slot name="title">Event Management</x-slot>

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                        <th class="p-4 font-semibold">Event Title</th>
                        <th class="p-4 font-semibold">Creator</th>
                        <th class="p-4 font-semibold">Category</th>
                        <th class="p-4 font-semibold">Date</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($events as $event)
                        <tr class="hover/5 transition-colors">
                            <td class="p-4">
                                <p class="font-bold line-clamp-1">{{ $event->title }}</p>
                                <p class="text-xs text-black">{{ ucfirst($event->event_type) }} | {{ ucfirst($event->registration_type) }}</p>
                            </td>
                            <td class="p-4 text-brand-500">{{ $event->creator?->name ?? 'N/A' }}</td>
                            <td class="p-4 text-brand-500">{{ $event->category?->name ?? 'N/A' }}</td>
                            <td class="p-4 text-brand-500 text-sm">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</td>
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
                                @if($event->status === 'pending')
                                    <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-500 hover text-white text-xs font-bold rounded-lg transition-colors">
                                            Approve
                                        </button>
                                    </form>

                                    <!-- Reject Form (Simple inline approach using JS prompt for reason) -->
                                    <form action="{{ route('admin.events.reject', $event->id) }}" method="POST" class="inline-block" id="reject-form-{{ $event->id }}">
                                        @csrf
                                        <input type="hidden" name="rejection_reason" id="reason-{{ $event->id }}">
                                        <button type="button" onclick="rejectEvent({{ $event->id }})" class="px-3 py-1 bg-amber-500 hover text-white text-xs font-bold rounded-lg transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500/20 hover/40 text-red-400 text-xs font-bold rounded-lg transition-colors" onclick="return confirm('Delete this event entirely?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-black">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100">
            {{ $events->links() }}
        </div>
    </div>

    <script>
        function rejectEvent(id) {
            let reason = prompt("Please enter the reason for rejection:");
            if (reason) {
                document.getElementById('reason-' + id).value = reason;
                document.getElementById('reject-form-' + id).submit();
            }
        }
    </script>
</x-admin-layout>
