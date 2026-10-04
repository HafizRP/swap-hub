@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-teal-500 text-start text-base font-medium text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/40 focus:outline-none focus:text-teal-800 dark:focus:text-teal-200 focus:bg-teal-100 dark:focus:bg-teal-900 focus:border-teal-700 dark:focus:border-teal-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-stone-600 dark:text-stone-400 hover:text-stone-800 dark:hover:text-stone-200 hover:bg-stone-50 dark:hover:bg-stone-800 hover:border-stone-300 dark:hover:border-stone-700 focus:outline-none focus:text-stone-800 dark:focus:text-stone-200 focus:bg-stone-50 dark:focus:bg-stone-800 focus:border-stone-300 dark:focus:border-stone-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
