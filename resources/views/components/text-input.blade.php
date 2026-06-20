@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'rounded-md border-gray-300 bg-white text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500']) }}>
