<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-white border-[1px] border-green-800 rounded-lg font-semibold text-xs text-green-800 dark:text-green-800 uppercase tracking-widest hover:shadow-lg hover:bg-green-800 hover:text-white dark:hover:bg-green-800 dark:hover:text-white focus:outline-none focus-visible:bg-green-800 focus-visible:text-white focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:bg-green-800 dark:focus-visible:text-white dark:focus-visible:ring-offset-green-800 active:bg-green-900 active:text-white dark:active:bg-green-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
