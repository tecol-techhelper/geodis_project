<?php

namespace App\Core\InternalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FailedSessionController extends Controller
{
    public function logFailedSession(
        string $username,
        ?string $ipAddress = null,
        ?string $userAgent = null): void
    {
        // Inserting the failed session values into the database
        DB::table('failed_logins')->insert([
            'username' => $username,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'attempted_at' => now(),
        ]);
    }
}
