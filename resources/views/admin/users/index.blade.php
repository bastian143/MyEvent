<x-admin-layout>
    <x-slot name="title">User Management</x-slot>

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3"><div class="w-1 h-6 bg-brand-500 rounded-full"></div><h2 class="font-bold text-black text-lg">Organizer Requests</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                        <th class="p-4 font-semibold">User</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Type</th>
                        <th class="p-4 font-semibold">Reason</th>
                        <th class="p-4 font-semibold">Requested At</th>
                        <th class="p-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($organizerRequests as $req)
                        <tr class="hover/5 transition-colors">
                            <td class="p-4 font-bold">{{ $req->user?->name }}</td>
                            <td class="p-4 text-brand-500">{{ $req->user->email }}</td>
                            <td class="p-4 font-bold">
                                @if($req->type === 'upgrade')
                                    <span class="text-emerald-400">Upgrade to Organizer</span>
                                @else
                                    <span class="text-amber-400">Downgrade to User</span>
                                @endif
                            </td>
                            <td class="p-4 text-brand-500 text-sm max-w-xs">{{ $req->reason ?? '-' }}</td>
                            <td class="p-4 text-brand-500">{{ $req->created_at->format('d M Y, H') }}</td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.users.handleOrganizerRequest', $req->id) }}" method="POST" class="flex justify-end gap-2">
                                    @csrf
                                    <button type="submit" name="status" value="approved" class="px-3 py-1 bg-emerald-500/20 hover/40 text-emerald-400 border-2 border-emerald-500/30 text-xs font-bold rounded-lg transition-colors">Approve</button>
                                    <button type="submit" name="status" value="rejected" class="px-3 py-1 bg-red-500/20 hover/40 text-red-400 border-2 border-red-500/30 text-xs font-bold rounded-lg transition-colors">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-black">No pending requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3"><div class="w-1 h-6 bg-brand-500 rounded-full"></div><h2 class="font-bold text-black text-lg">All Users</h2></div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Role</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover/5 transition-colors">
                            <td class="p-4 font-bold">{{ $user->name }}</td>
                            <td class="p-4 text-brand-500">{{ $user->email }}</td>
                            <td class="p-4">
                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <select name="role" class="bg-white border border-gray-100 rounded-lg pl-3 pr-8 py-1 text-sm text-black focus outline-none">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-brand-500 hover text-white text-xs font-bold rounded-lg transition-colors">
                                        Save
                                    </button>
                                </form>
                            </td>
                            <td class="p-4">
                                @if($user->is_blocked)
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs font-bold rounded">Blocked</span>
                                @else
                                    <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-bold rounded">Active</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.users.toggleBlock', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @if($user->is_blocked)
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500/10 hover/20 text-emerald-400 border-2 border-emerald-500/30 text-xs font-bold rounded-lg transition-colors">
                                            Unblock
                                        </button>
                                    @else
                                        <button type="submit" class="px-3 py-1.5 bg-red-500/10 hover/20 text-red-400 border-2 border-red-500/30 text-xs font-bold rounded-lg transition-colors" onclick="return confirm('Are you sure you want to block this user?')">
                                            Block
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
