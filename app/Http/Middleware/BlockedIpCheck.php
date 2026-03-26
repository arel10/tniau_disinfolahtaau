<?php

namespace App\Http\Middleware;

use App\Services\SecurityAuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockedIpCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (SecurityAuditService::isIpBlocked($request->ip())) {
            abort(403, 'Akses dari alamat IP Anda telah diblokir sementara karena aktivitas mencurigakan.');
        }

        return $next($request);
    }
}
