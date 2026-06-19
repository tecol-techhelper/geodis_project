<ol class="flex w-full flex-wrap items-center gap-y-2 rounded-md text-sm">
    @foreach ($items as $index => $item)
        @if (isset($item['url']) && count($items) - 1)
            <li class="flex items-center text-blue-600 transition-colors duration-150 hover:text-blue-800">
                <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4"></i>
                <a href="{{ $item['url'] }}" class="px-2 hover:underline">{{ $item['label'] }}</a>
                <span class="pointer-events-none mx-1 text-gray-400">
                    /
                </span>
            </li>
        @else
            <li class="flex items-center font-medium text-gray-800">
                <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4 text-gray-500"></i>
                <span class="px-2">{{ $item['label'] }}</span>
            </li>
        @endif
    @endforeach
</ol>
