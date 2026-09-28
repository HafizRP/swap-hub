@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none shadow-sm transition-all']) }}>
