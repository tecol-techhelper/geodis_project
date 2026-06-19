@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => "block w-full rounded-md border border-gray-300 bg-white p-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500",
'placeholder' => '']) }}></textarea>
