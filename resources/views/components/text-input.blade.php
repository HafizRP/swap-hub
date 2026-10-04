@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-stone-300 dark:border-stone-700 dark:bg-[#1c1a17] dark:text-stone-100 focus:border-teal-600 dark:focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm text-sm']) }}>
