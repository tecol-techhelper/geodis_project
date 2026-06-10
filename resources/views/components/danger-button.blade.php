<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-white border-[1px] border-red-600 rounded-lg font-semibold text-xs text-red-600 dark:text-red-600 uppercase tracking-widest hover:shadow-lg hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white focus:outline-none focus-visible:bg-red-600 focus-visible:text-white focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:bg-red-600 dark:focus-visible:text-white dark:focus-visible:ring-offset-red-600 active:bg-red-900 active:text-white dark:active:bg-red-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
