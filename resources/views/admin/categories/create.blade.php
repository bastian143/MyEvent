<x-admin-layout>
    <x-slot name="title">Create Category</x-slot>

    <div class="max-w-2xl mx-auto bg-white border border-gray-100 rounded-xl shadow-sm rounded-2xl border border-gray-100 p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-500 mb-2">Category Name</label>
                <input type="text" name="name" required class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus focus outline-none transition-all">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-500 mb-2">Description (Optional)</label>
                <textarea name="description" rows="4" class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus focus outline-none transition-all"></textarea>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-brand-500 hover text-white font-bold rounded-xl transition-colors">Save Category</button>
            </div>
        </form>
    </div>
</x-admin-layout>
