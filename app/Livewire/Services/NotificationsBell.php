<?php

namespace App\Livewire\Services;

use App\Models\Notification;
use Livewire\Component;

class NotificationsBell extends Component
{

    // Marking notifications as readed
    public function markAllAsRead():void
    {
        Notification::where('is_read',false)->update(['is_read'=>true]);
        // $this->loadNotifications();
    }

    // Mark an specific notific as readed
    public function markAsRead($notificationId):void
    {
        $notIf = Notification::where('id',$notificationId)->first();

        // For avoing no existing and readed notifications
        if($notIf && !$notIf->is_read){
            $notIf->is_read = true;
            $notIf->save();
            // $this->loadNotifications();
        }
    }



    public function render()
    {
        return view('livewire.services.notifications-bell', [
        'unreadCount' => Notification::where('is_read', false)->count(),
        'notifications' => Notification::where('is_read', false)
            ->latest()
            ->get(),
    ]);
    }
}
