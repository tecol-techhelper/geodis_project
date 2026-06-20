<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-md border border-red-600 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-red-600 transition duration-150 ease-in-out hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-900 disabled:cursor-not-allowed disabled:opacity-50']) }}>
    {{ $slot }}
</button>
