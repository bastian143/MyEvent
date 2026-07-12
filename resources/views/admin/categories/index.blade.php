<x-admin-layout>
    <x-slot name="title">Categories</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold">Manage Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-brand-500 hover text-white font-bold rounded-xl transition-colors">
            + New Category
        </a>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold text-black">
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Description</th>
                        <th class="p-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                        <tr class="hover/5 transition-colors">
                            <td class="p-4 font-bold">{{ $category->name }}</td>
                            <td class="p-4 text-brand-500">{{ $category->description ?? '-' }}</td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500/20 hover/40 text-red-400 text-xs font-bold rounded-lg transition-colors" onclick="return confirm('Delete this category?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-black">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
