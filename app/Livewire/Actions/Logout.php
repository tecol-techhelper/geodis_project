<?php

namespace App\Livewire\Actions;

use App\Core\InternalControllers\SessionLogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        $session = session()->getId();
        $userId =  Auth::id();

        (new SessionLogController())->logoutUpdateSession($session, $userId);

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
