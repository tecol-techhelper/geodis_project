<ol class="flex w-full rounded-md px-4 py-2">
    @foreach ($items as $index => $item)
        @if (isset($item['url']) && count($items) - 1)
            <li class="flex cursor-pointer text-sm text-slate-500 transition-colors duration-300 hover:text-slate-800">
                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                <span class="pointer-events-none mx-2 text-slate-800">
                    /
                </span>
            </li>
        @else
            <li class="flex cursor-pointer text-sm text-slate-500 transition-colors duration-300 hover:text-slate-800">
                {{ $item['label'] }}

            </li>
        @endif
    @endforeach
</ol>
