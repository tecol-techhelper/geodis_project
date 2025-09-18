<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BlockedIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $blockedIP = DB::table('blocked_ips')
            ->where('ip_address', $request->ip())
            ->where('ip_status', 'blocked')
            ->exists();

        if($blockedIP){
            abort(403, 'Acceso denegado. Esta ip ha sido bloqueada por el sistema');
        }   
        return $next($request);
    }
}
