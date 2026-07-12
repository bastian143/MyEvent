<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-black leading-tight">
                {{ __('Issue Certificates: ' . $event->title) }}
            </h2>
            <a href="{{ route('organizer.events.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl border border-slate-200 transition-colors">
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
                                <th class="p-4 font-semibold">Participant Name</th>
                                <th class="p-4 font-semibold">Email</th>
                                <th class="p-4 font-semibold">Registration Status</th>
                                <th class="p-4 font-semibold">Upload Certificate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($participants as $registration)
                                <tr class="hover/5 transition-colors">
                                    <td class="p-4 font-bold text-black">{{ $registration->user?->name }}</td>
                                    <td class="p-4 text-brand-500">{{ $registration->user->email }}</td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 bg-brand-500/20 text-brand-300 text-xs font-bold rounded">{{ ucfirst($registration->status) }}</span>
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $certificate = \App\Models\Certificate::where('user_id', $registration->user_id)->where('event_id', $event->id)->first();
                                        @endphp
                                        
                                        @if($certificate)
                                            <div class="flex items-center gap-3">
                                                <a href="{{ Storage::url($certificate->file_path) }}" target="_blank" class="px-3 py-1.5 bg-brand-500/20 hover/40 text-brand-300 text-xs font-bold rounded-lg border-2 border-brand-500/30 transition-colors">
                                                    View Document
                                                </a>
                                                <button onclick="document.getElementById('reupload-form-{{ $registration->user_id }}').classList.toggle('hidden')" class="px-3 py-1.5 bg-slate-50 hover/10 text-brand-500 text-xs font-bold rounded-lg border border-gray-100 transition-colors">
                                                    Re-upload
                                                </button>
                                            </div>
                                            
                                            <form id="reupload-form-{{ $registration->user_id }}" action="{{ route('organizer.events.certificates.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="hidden mt-3 flex gap-2">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $registration->user_id }}">
                                                <input type="file" name="certificate_file" accept=".pdf,.jpg,.png" required class="block w-full text-xs text-brand-500 file file file file file file[10px] file file file hover/10 border border-gray-100 rounded-full bg-slate-50">
                                                <button type="submit" class="px-3 py-1 bg-brand-500 hover text-white text-[10px] font-bold rounded-full transition-colors">
                                                    Save
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('organizer.events.certificates.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $registration->user_id }}">
                                                <input type="file" name="certificate_file" accept=".pdf,.jpg,.png" required class="block w-full text-sm text-brand-500 file file file file file file file file file hover/10 border border-gray-100 rounded-full bg-slate-50">
                                                <button type="submit" class="px-4 py-1 bg-brand-500 hover text-white text-xs font-bold rounded-full transition-colors">
                                                    Upload
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-black">No participants have registered for this event yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            

        </div>
    </div>
</x-app-layout>
