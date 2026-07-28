<?php

namespace App\Livewire\Services;

use App\Models\Notification;
use Livewire\Component;

class NotificationsBell extends Component
{
    private const VISIBLE_NOTIFICATIONS_LIMIT = 20;

    public function markAllAsRead(): void
    {
        Notification::query()
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function markAsRead(int $notificationId): void
    {
        $notification = Notification::query()->find($notificationId);

        if ($notification && ! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }
    }

    public function render()
    {
        return view('livewire.services.notifications-bell', [
            'unreadCount' => Notification::query()
                ->where('is_read', false)
                ->count(),
            'notifications' => Notification::query()
                ->select(['id', 'title', 'message', 'purchase_order'])
                ->where('is_read', false)
                ->latest()
                ->limit(self::VISIBLE_NOTIFICATIONS_LIMIT)
                ->get(),
        ]);
    }
}
