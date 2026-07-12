<x-admin-layout>
    <x-slot name="title">Dashboard Overview</x-slot>

    <div class="grid grid-cols-1 md lg gap-6 mb-8">
        <!-- Stat Cards -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-md overflow-hidden relative group border-t-4 border-t-brand-500 p-6">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover transition-opacity">
                <svg class="w-16 h-16 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-black text-sm font-bold mb-1">Total Users</h3>
            <p class="text-4xl font-extrabold text-black">{{ number_format($stats['total_users']) }}</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-md overflow-hidden relative group border-t-4 border-t-brand-500 p-6">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover transition-opacity">
                <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-black text-sm font-bold mb-1">Total Events</h3>
            <p class="text-4xl font-extrabold text-black">{{ number_format($stats['total_events']) }}</p>
        </div>

        <div class="bg-amber-50 border-2 border-amber-200 rounded-xl shadow-sm p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover transition-opacity">
                <svg class="w-16 h-16 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-amber-800 text-sm font-bold mb-1">Pending Approvals</h3>
            <p class="text-4xl font-extrabold text-amber-600">{{ number_format($stats['pending_events']) }}</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-md overflow-hidden relative group border-t-4 border-t-brand-500 p-6">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover transition-opacity">
                <svg class="w-16 h-16 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <h3 class="text-black text-sm font-bold mb-1">Categories</h3>
            <p class="text-4xl font-extrabold text-black">{{ number_format($stats['total_categories']) }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg gap-8">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-md border-t-4 border-t-brand-500 p-6">
            <h3 class="text-lg font-bold mb-4 text-black">Quick Links</h3>
            <div class="flex flex-col gap-3">
                <a href="{{ route('admin.events.index') }}" class="px-4 py-3 bg-slate-50 border border-gray-100 rounded-xl hover transition-colors flex justify-between items-center text-black font-medium">
                    <span>Review Pending Events</span>
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-3 bg-slate-50 border border-gray-100 rounded-xl hover transition-colors flex justify-between items-center text-black font-medium">
                    <span>Manage Users</span>
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
