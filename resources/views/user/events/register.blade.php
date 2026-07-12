<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Register: ') }} {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto bg-white border border-gray-100 rounded-xl shadow-sm rounded-2xl border border-gray-100 p-8">
            
            @if($errors->any())
                <div class="p-4 mb-6 rounded-xl bg-red-500/20 border-2 border-red-500/30 text-red-300 font-semibold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.events.register', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                @if($event->formFields->count() > 0)
                    <p class="text-brand-500 mb-6">Please fill out the required information requested by the organizer below.</p>
                    
                    @foreach($event->formFields as $field)
                        <div>
                            <label class="block text-sm font-semibold text-brand-500 mb-2">
                                {{ $field->name }}
                                @if($field->is_required) <span class="text-red-400">*</span> @endif
                            </label>

                            @if($field->type === 'text')
                                <input type="text" name="answers[{{ $field->id }}]" {{ $field->is_required ? 'required' : '' }} class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                            @elseif($field->type === 'email')
                                <input type="email" name="answers[{{ $field->id }}]" {{ $field->is_required ? 'required' : '' }} class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                            @elseif($field->type === 'textarea')
                                <textarea name="answers[{{ $field->id }}]" rows="3" {{ $field->is_required ? 'required' : '' }} class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none"></textarea>
                            @elseif($field->type === 'select')
                                <select name="answers[{{ $field->id }}]" {{ $field->is_required ? 'required' : '' }} class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus outline-none">
                                    <option value="" disabled selected>Select an option</option>
                                    @foreach($field->options as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @elseif($field->type === 'file')
                                <input type="file" name="files[{{ $field->id }}]" accept=".pdf,.jpg,.png" {{ $field->is_required ? 'required' : '' }} class="w-full bg-white border border-gray-100 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 focus outline-none file file file file file file file file file hover">
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-brand-500 mb-6">No additional information is required. Just click submit to register!</p>
                @endif

                <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                    <a href="{{ route('front.events.show', $event->slug) }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">Cancel</a>
                    <button type="submit" class="px-8 py-3 bg-brand-500 hover text-white font-bold rounded-xl shadow-lg transition-transform hover">
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
