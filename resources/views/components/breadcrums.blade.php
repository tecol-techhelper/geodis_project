<ol class="flex w-full rounded-md px-4 py-2">
    @foreach ($items as $index => $item)
        @if (isset($item['url']) && count($items) - 1)
            <li class="flex cursor-pointer text-sm text-blue-400 transition-colors duration-300 hover:text-blue-800 items-center">
                <i data-lucide="{{ $item['icon'] }}" class="w-4"></i>
                <a href="{{ $item['url'] }}" class="hover:underline px-2">{{ $item['label'] }}</a>
                <span class="pointer-events-none mx-2 text-slate-800">
                    /
                </span>
            </li>
        @else
            <li class="flex cursor-pointer text-sm text-blue-400 transition-colors duration-300 items-center">
                <i data-lucide="{{ $item['icon'] }}" class="w-4"></i>
                <span class="px-2">{{ $item['label'] }}</span>
            </li>
        @endif
    @endforeach
</ol>
