<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-black leading-tight">
                Dashboard
            </h2>
            <p class="text-lg font-bold text-black uppercase tracking-wide">{{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Ringkasan Aktivitas -->
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-1 h-6 bg-brand-500 rounded-full"></div>
                    <h3 class="font-bold text-black">Ringkasan Aktivitas</h3>
                </div>
                <div class="overflow-x-auto p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                                <th class="p-4">Lomba Terdaftar</th>
                                <th class="p-4">Lomba Selesai</th>
                                <th class="p-4">Prestasi/Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="p-4 text-sm font-bold text-gray-900">{{ $stats['registered'] }}</td>
                                <td class="p-4 text-sm font-bold text-gray-900">{{ $stats['completed'] }}</td>
                                <td class="p-4 text-sm font-bold text-gray-900">{{ $stats['certificates'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Jadwal & Deadline Terdekat -->
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-1 h-6 bg-brand-500 rounded-full"></div>
                    <h3 class="font-bold text-black">Jadwal & Deadline Terdekat</h3>
                </div>
                <div class="p-6">
                    @if($upcomingEvents->count() > 0)
                        <ul class="space-y-2">
                            @foreach($upcomingEvents as $reg)
                                <li class="text-sm font-medium text-black flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    {{ $reg->event->title }}, {{ \Carbon\Carbon::parse($reg->event->start_date)->translatedFormat('d M Y') }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-black">Tidak ada jadwal terdekat.</p>
                    @endif
                </div>
            </div>

            <!-- Status Pendaftaran -->
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-1 h-6 bg-brand-500 rounded-full"></div>
                    <h3 class="font-bold text-black">Status Pendaftaran</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                                <th class="p-4 w-1/3">Nama Lomba</th>
                                <th class="p-4 w-1/4">Kategori</th>
                                <th class="p-4 w-1/4">Status</th>
                                <th class="p-4 w-1/6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($registeredEvents as $reg)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-sm font-bold text-black">{{ $reg->event->title }}</td>
                                    <td class="p-4 text-sm text-black">{{ $reg->event->category?->name ?? '-' }}</td>
                                    <td class="p-4 text-sm text-black font-medium">
                                        {{ $reg->status == 'approved' ? 'Terdaftar' : ($reg->status == 'pending' ? 'Menunggu Persetujuan' : 'Ditolak') }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('front.events.show', $reg->event->slug) }}" class="text-xs font-bold text-brand-500 hover:underline">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-sm text-black">Belum ada pendaftaran event.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tim Saya (My Teams) -->
            @if(isset($teams) && $teams->count() > 0)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-1 h-6 bg-brand-500 rounded-full"></div>
                    <h3 class="font-bold text-black">Tim Saya</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                                <th class="p-4 w-1/3">Nama Tim</th>
                                <th class="p-4 w-1/3">Event</th>
                                <th class="p-4 w-1/6">Status Saya</th>
                                <th class="p-4 w-1/6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($teams as $teamMember)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-sm font-bold text-black">{{ $teamMember->team->name }}</td>
                                    <td class="p-4 text-sm text-black">{{ $teamMember->team->event->title }}</td>
                                    <td class="p-4 text-sm text-black font-medium">
                                        @if($teamMember->status === 'joined')
                                            <span class="px-2 py-1 bg-emerald-500/20 text-emerald-600 text-xs font-bold rounded">Anggota</span>
                                        @elseif($teamMember->status === 'pending')
                                            <span class="px-2 py-1 bg-amber-500/20 text-amber-600 text-xs font-bold rounded">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('user.teams.show', $teamMember->team_id) }}" class="px-3 py-1.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-lg transition-colors inline-block">Kelola Tim</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Rekomendasi Rekan Tim -->
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-1 h-6 bg-brand-500 rounded-full"></div>
                    <h3 class="font-bold text-black">Rekomendasi Rekan Tim</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                                <th class="p-4">Nama Tim / Leader</th>
                                <th class="p-4">Event</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recommendedTeams as $team)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-sm font-bold text-black">
                                        {{ $team->name }} <span class="text-xs font-normal text-black">({{ $team->leader?->name }})</span>
                                    </td>
                                    <td class="p-4 text-sm text-black">{{ $team->event->title }}</td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('front.events.show', $team->event->slug) }}" class="text-xs font-bold text-brand-500 hover:underline">Gabung / Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-sm text-black">Tidak ada rekomendasi tim saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Two Column Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Lomba Tersimpan -->
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md h-full flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-1 h-6 bg-brand-500 rounded-full"></div>
                        <h3 class="font-bold text-black">Lomba Tersimpan</h3>
                    </div>
                    <div class="p-4 flex-1">
                        @if($savedEvents->count() > 0)
                            <ul class="space-y-3">
                                @foreach($savedEvents as $event)
                                    <li class="flex justify-between items-center bg-slate-50 p-3 rounded-lg border border-gray-100">
                                        <div>
                                            <h4 class="font-bold text-sm text-black">{{ $event->title }}</h4>
                                            <p class="text-xs text-black">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</p>
                                        </div>
                                        <a href="{{ route('front.events.show', $event->slug) }}" class="px-3 py-1.5 bg-brand-100 hover:bg-brand-200 text-brand-600 text-xs font-bold rounded-lg transition-colors">
                                            Lihat
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="h-full flex items-center justify-center min-h-[100px]">
                                <p class="text-sm text-black">Belum ada lomba tersimpan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Rekomendasi Lomba Untuk Kamu -->
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md h-full flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-1 h-6 bg-brand-500 rounded-full"></div>
                        <h3 class="font-bold text-black">Rekomendasi Lomba Untuk Kamu</h3>
                    </div>
                    <div class="p-4 flex-1">
                        @if($recommendedEvents->count() > 0)
                            <ul class="space-y-3">
                                @foreach($recommendedEvents as $event)
                                    <li class="flex justify-between items-center bg-slate-50 p-3 rounded-lg border border-gray-100">
                                        <div>
                                            <h4 class="font-bold text-sm text-black line-clamp-1">{{ $event->title }}</h4>
                                            <p class="text-xs text-black">{{ $event->category?->name ?? 'Uncategorized' }}</p>
                                        </div>
                                        <a href="{{ route('front.events.show', $event->slug) }}" class="px-3 py-1.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-lg transition-colors">
                                            Daftar
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="h-full flex items-center justify-center min-h-[100px]">
                                <p class="text-sm text-black">Tidak ada rekomendasi saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
