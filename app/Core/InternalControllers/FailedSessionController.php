<?php

namespace App\Core\InternalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FailedSessionController extends Controller
{
    public function logFailedSession(
        string $username,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $reason = null): void
    {
        if (!Schema::hasTable('failed_logins')) {
            return;
        }

        // Inserting the failed session values into the database
        DB::table('failed_logins')->insert([
            'username' => $username,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'reason' => $reason,
            'attempted_at' => now(),
        ]);
    }
}
