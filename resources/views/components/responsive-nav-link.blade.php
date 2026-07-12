@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400  text-start text-base font-medium text-indigo-700  bg-indigo-50 /50 focus:outline-none focus:text-indigo-800 :text-indigo-200 focus:bg-indigo-100 :bg-indigo-900 focus:border-indigo-700 :border-indigo-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-gray-200 text-start text-base font-medium text-black  hover:text-black :text-gray-200 hover:bg-gray-50 :bg-gray-700 hover:border-gray-200 :border-gray-600 focus:outline-none focus:text-black :text-gray-200 focus:bg-gray-50 :bg-gray-700 focus:border-gray-200 :border-gray-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
