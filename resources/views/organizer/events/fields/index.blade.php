<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-black leading-tight">
                {{ __('Form Builder: ') }} {{ $event->title }}
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
            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-500/20 border-2 border-red-500/30 text-red-300 font-semibold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg gap-8">
                <!-- Add New Field Form -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-md p-6 h-fit">
                    <h3 class="text-xl font-bold mb-4">Add Custom Field</h3>
                    <form action="{{ route('organizer.events.fields.store', $event->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-brand-500 mb-1">Field Name (Question)</label>
                            <input type="text" name="name" required placeholder="e.g. T-Shirt Size" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-2 text-slate-800 placeholder-slate-400 focus outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-brand-500 mb-1">Input Type</label>
                            <select name="type" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-2 text-slate-800 placeholder-slate-400 focus outline-none">
                                <option value="text">Short Text</option>
                                <option value="textarea">Long Text (Paragraph)</option>
                                <option value="email">Email</option>
                                <option value="select">Dropdown (Select)</option>
                                <option value="file">File Upload (PDF/Image)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-brand-500 mb-1">Options (For Dropdown)</label>
                            <input type="text" name="options" placeholder="S, M, L, XL" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-2 text-slate-800 placeholder-slate-400 focus outline-none">
                            <p class="text-xs text-black mt-1">Separate with commas. Leave blank if not dropdown.</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_required" id="is_required" value="1" checked class="rounded border-gray-100 bg-white text-brand-500 focus">
                            <label for="is_required" class="text-sm text-brand-500">Required Field</label>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-brand-500 hover text-white font-bold rounded-xl transition-colors">
                            Add Field
                        </button>
                    </form>
                </div>

                <!-- Existing Fields List -->
                <div class="lg bg-white border border-gray-100 rounded-2xl shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4">Registration Form Preview</h3>
                    <p class="text-sm text-black mb-6">This is how your registration form will look to participants.</p>
                    
                    <div class="space-y-4">
                        @forelse($fields as $field)
                            <div class="p-4 border border-gray-100 rounded-xl bg-slate-50 flex justify-between items-start group">
                                <div class="flex-1">
                                    <label class="block font-bold text-black mb-1">
                                        {{ $field->name }}
                                        @if($field->is_required)
                                            <span class="text-red-400">*</span>
                                        @endif
                                    </label>
                                    
                                    @if($field->type === 'text' || $field->type === 'email')
                                        <input type="text" disabled placeholder="Participant will type here..." class="w-full bg-white/50 border border-gray-100 rounded-lg px-3 py-2 text-black text-sm">
                                    @elseif($field->type === 'textarea')
                                        <textarea disabled rows="2" placeholder="Participant will type here..." class="w-full bg-white/50 border border-gray-100 rounded-lg px-3 py-2 text-black text-sm"></textarea>
                                    @elseif($field->type === 'select')
                                        <select disabled class="w-full bg-white/50 border border-gray-100 rounded-lg px-3 py-2 text-black text-sm">
                                            @foreach($field->options as $opt)
                                                <option>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($field->type === 'file')
                                        <div class="w-full bg-white/50 border-2 border-dashed border-white/20 rounded-lg px-3 py-4 text-center text-black text-sm">
                                            File Upload Box
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 opacity-0 group-hover transition-opacity">
                                    <form action="{{ route('organizer.events.fields.destroy', [$event->id, $field->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-400 hover/20 rounded-lg transition-colors" onclick="return confirm('Delete this field?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center border-2 border-dashed border-gray-100 rounded-xl">
                                <p class="text-black">No custom fields added yet.<br>Participants will only need to click "Register" if no fields are added.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
