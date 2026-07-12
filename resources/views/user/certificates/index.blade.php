<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-black leading-tight">
            {{ __('My Certificates') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-500/20 border-2 border-emerald-500/30 text-emerald-300 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Upload External Certificate -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 border-2 border-brand-500/30 rounded-2xl">
                <h3 class="text-xl font-bold mb-4">Upload External Certificate</h3>
                <p class="text-sm text-black mb-6">Store certificates you've earned outside of MyEvent here to build your portfolio.</p>
                <form action="{{ route('user.certificates.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md gap-4 items-start md">
                    @csrf
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Certificate Title</label>
                        <input type="text" name="title" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">File (PDF/Image)</label>
                        <input type="file" name="certificate_file" accept=".pdf,.jpg,.png" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-2 text-slate-800 placeholder-slate-400 focus outline-none file file file file file file file file file hover">
                    </div>
                    <button type="submit" class="w-full md px-6 py-2.5 bg-brand-500 hover text-white font-bold rounded-xl shadow-lg transition-transform hover">
                        Upload
                    </button>
                </form>
            </div>

            <!-- Certificates Gallery -->
            <div>
                <h3 class="text-2xl font-bold mb-6">My Certificate Gallery</h3>
                
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                                    <th class="p-4">Certificate Name</th>
                                    <th class="p-4">Certificate Number</th>
                                    <th class="p-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($certificates as $cert)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="p-4 font-bold text-gray-900">
                                            {{ $cert->event ? $cert->event->title : ($cert->title ?? 'External Certificate') }}
                                        </td>
                                        <td class="p-4 text-sm text-gray-600 font-mono">
                                            {{ $cert->certificate_number }}
                                        </td>
                                        <td class="p-4">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ Storage::url($cert->file_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-bold rounded-lg transition-colors">
                                                    View
                                                </a>
                                                <a href="{{ Storage::url($cert->file_path) }}" download class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-xs font-bold rounded-lg transition-colors">
                                                    Download
                                                </a>
                                                <form action="{{ route('user.certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this certificate?');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-8 text-center bg-white">
                                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                                <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">No Certificates Yet</h3>
                                            <p class="text-gray-500">Attend events or upload your external certificates here.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
