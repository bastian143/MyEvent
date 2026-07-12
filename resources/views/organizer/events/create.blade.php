<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-black leading-tight">
                {{ __('Create New Event') }}
            </h2>
            <a href="{{ route('organizer.events.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl border border-slate-200 transition-colors">
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto bg-white border border-gray-100 rounded-xl shadow-sm rounded-2xl border border-gray-100 p-8">
            <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-red-500/20 border-2 border-red-500/30 text-red-300 text-sm font-semibold mb-6">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md gap-6">
                    <div class="md">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Event Title</label>
                        <input type="text" name="title" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus focus outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Category</label>
                        <select name="category_id" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Event Type</label>
                        <select name="event_type" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Start Date</label>
                        <input type="datetime-local" name="start_date" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">End Date</label>
                        <input type="datetime-local" name="end_date" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>
                    
                    <div class="md">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Location / Link</label>
                        <input type="text" name="location" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>

                    <div class="md">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Description</label>
                        <textarea name="description" rows="5" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none"></textarea>
                    </div>

                    <div x-data="{ regType: 'individual' }">
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Registration Type</label>
                        <select name="registration_type" x-model="regType" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                            <option value="individual">Individual</option>
                            <option value="team">Team Based</option>
                        </select>
                        
                        <div x-show="regType === 'team'" class="mt-4" style="display: none;">
                            <label class="block text-sm font-semibold text-brand-500 mb-2">Max Members per Team</label>
                            <input type="number" name="team_size" min="2" placeholder="e.g. 3" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                            <p class="text-xs text-black mt-1">Maximum number of people allowed in a single team.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Price (Rp)</label>
                        <input type="number" name="price" value="0" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Quota</label>
                        <input type="number" name="quota" placeholder="Leave empty for unlimited" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Registration Deadline</label>
                        <input type="datetime-local" name="registration_deadline" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Contact Person Name</label>
                        <input type="text" name="contact_person" required placeholder="e.g. Budi (WhatsApp)" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Contact Phone Number</label>
                        <input type="text" name="contact_phone" required placeholder="e.g. 081234567890" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-500 mb-2">Event Poster <span class="text-red-500">*</span></label>
                        <input type="file" name="poster" accept="image/*" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 focus outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                        <p class="text-xs text-black mt-1">This is the main poster shown on the event details page.</p>
                    </div>
                </div>

                <!-- Dynamic Registration Form Fields -->
                <div class="pt-8 border-t border-gray-100" x-data="{
                    fields: [
                        { id: Date.now(), type: 'text', label: '', is_required: true }
                    ],
                    addField() {
                        this.fields.push({ id: Date.now(), type: 'text', label: '', is_required: true });
                    },
                    removeField(index) {
                        this.fields.splice(index, 1);
                    }
                }">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-black">Custom Registration Form</h3>
                            <p class="text-sm text-black">Add custom fields that participants must fill out when registering.</p>
                        </div>
                        <button type="button" @click="addField" class="px-4 py-2 bg-brand-500/20 text-brand-300 hover/30 font-bold rounded-lg transition-colors border-2 border-brand-500/30 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Field
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(field, index) in fields":key="field.id">
                            <div class="bg-white border border-gray-100 rounded-2xl shadow-md p-4 flex flex-col md gap-4 items-start md">
                                <div class="flex-1 w-full">
                                    <label class="block text-xs font-semibold text-black mb-1">Field Label / Question</label>
                                    <input type="text" x-model="field.label":name="'form_fields['+index+'][label]'" required placeholder="e.g. University Name, T-Shirt Size" class="w-full bg-slate-50 border border-gray-100 rounded-lg px-3 py-2 text-slate-800 placeholder-slate-400 focus outline-none text-sm">
                                </div>
                                <div class="w-full md">
                                    <label class="block text-xs font-semibold text-black mb-1">Field Type</label>
                                    <select x-model="field.type":name="'form_fields['+index+'][type]'" class="w-full bg-slate-50 border border-gray-100 rounded-lg px-3 py-2 text-slate-800 placeholder-slate-400 focus outline-none text-sm">
                                        <option value="text">Short Text</option>
                                        <option value="textarea">Long Text</option>
                                        <option value="email">Email</option>
                                        <option value="file">File Upload (PDF/Image)</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-4 h-10 w-full md mt-4 md">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="field.is_required" class="w-4 h-4 rounded border-gray-100 bg-slate-50 text-brand-500 focus">
                                        <input type="hidden":name="'form_fields['+index+'][is_required]'":value="field.is_required ? 1 : 0">
                                        <span class="text-sm font-semibold text-brand-500">Required</span>
                                    </label>
                                    
                                    <button type="button" @click="removeField(index)" class="p-2 bg-red-500/10 hover/20 text-red-400 rounded-lg transition-colors ml-auto md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <div x-show="fields.length === 0" class="text-center py-6 border-2 border-dashed border-gray-100 rounded-xl text-black text-sm">
                            No custom fields added. Only standard details will be collected.
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl shadow-lg transition-transform hover">
                        Submit Event for Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
