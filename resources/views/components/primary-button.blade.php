<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-500 text-white  border border-gray-100 rounded-md font-semibold text-xs text-white  uppercase tracking-widest hover:bg-gray-700 :bg-white focus:bg-gray-700 :bg-white active:bg-brand-500 text-white :bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
