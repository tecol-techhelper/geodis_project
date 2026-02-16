<?php

namespace App\Core\InternalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockedIpController extends Controller
{
    // Checking if an ip address is blocked
    public function isBlocked(string $ipAddress): bool
    {
        return DB::table('blocked_ips')
            ->where('ip_address', $ipAddress)
            ->where('ip_status', 'blocked')
            ->exists();
    }

    // Saving the blocked ip address to the database
    public function blockIp(string $ipAddress, ?string $userAgent=null):void{
        DB::table('blocked_ips')->insert([
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'blocked_at' => now(),
            'ip_status' => 'blocked',
        ]);
    }

    //Evaluate and block ip address if necessary
    public function evaluateAndBlockIp(string $ipAddress):void
    {
        $recentTries = DB::table('failed_logins')
        ->where('ip_address', $ipAddress)
            ->where('attempted_at', '>=', now()->subMinutes(5))
            ->count();

        // $disticnUsers = DB::table('failed_logins')
        // ->where('ip_address', $ipAddress)
        //     ->distinct()
        //     ->count('user_id');

        if ($recentTries >= 10) {
            (new BlockedIpController())->blockIp($ipAddress);
        }

    }
}
