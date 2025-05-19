<div wire:poll.10s wire:key="notifications-bell" class="relative" x-data="{ open: false }">
    {{-- Botón de campana --}}
    <button @click="open = !open" class="relative focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor"
            stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-bell-icon lucide-bell">
            <path d="M10.268 21a2 2 0 0 0 3.464 0" />
            <path
                d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute top-0 right-0 bg-blue-600 text-white text-xs rounded-full px-1">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" @click.outside="open = false"
        class="absolute right-0 z-10 mt-2 bg-white border rounded shadow w-64">
        <ul class="max-h-60 overflow-y-auto p-2">
            @forelse ($notifications as $notif)
                <li class="border-b py-1 text-sm cursor-pointer hover:bg-gray-100"
                    wire:click="markAsRead({{ $notif->id }})">
                    <strong>{{ $notif->title }}</strong><br>
                    {{ $notif->message }}<br>
                    <span class="text-xs text-gray-500">Orden: {{ $notif->purchase_order }}</span>
                </li>
            @empty
                <li class="py-2 text-center text-gray-500">Sin notificaciones</li>
            @endforelse
        </ul>

        @if ($unreadCount > 0)
            <button wire:click="markAllAsRead" class="w-full text-blue-600 py-1 hover:bg-gray-100">
                Marcar todas como leídas
            </button>
        @endif
    </div>
</div>
