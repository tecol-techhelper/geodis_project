<?php

namespace App\Core\InternalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionLogController extends Controller
{
    public function logSession(
        int $userID,
        string $username,
        string $userRole,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $sessionToken = null): void
    {
        // Inserting the session values into the database
        DB::table('session_logs')->insert([
            'user_id' => $userID,
            'username' => $username,
            'user_rol' => $userRole,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'session_token' => $sessionToken,
            'login_at' => now(),
        ]);
    }

    // Updating current session logout
    public function logoutUpdateSession($session, $userId): void{
        DB::table('session_logs')
        ->where('user_id',$userId)
        ->where('session_token',$session)
        ->update(['logout_at'=>now()]);
    }
}
