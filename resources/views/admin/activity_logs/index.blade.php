<x-admin-layout>
    <x-slot name="title">System Activity Logs</x-slot>

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                        <th class="p-4 font-semibold">Timestamp</th>
                        <th class="p-4 font-semibold">User</th>
                        <th class="p-4 font-semibold">Action Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover/5 transition-colors">
                            <td class="p-4 text-black text-sm whitespace-nowrap">{{ $log->created_at->format('M d, Y H') }}</td>
                            <td class="p-4 font-bold text-brand-600">{{ $log->user?->name ?? 'System' }}</td>
                            <td class="p-4 text-black">{{ $log->action }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-black">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</x-admin-layout>
