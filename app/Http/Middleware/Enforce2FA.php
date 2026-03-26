<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Enforce2FA
{
    /**
     * Routes that are exempt from 2FA enforcement.
     */
    protected array $except = [
        'two-factor.*',
        'logout',
        'login',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Skip exempt routes
        foreach ($this->except as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // If user has 2FA enabled: require verification each session
        if ($user->hasTwoFactorEnabled()) {
            if (!session('two_factor_verified')) {
                return redirect()->route('two-factor.challenge');
            }
        }

        // If admin role but 2FA not yet enabled: force setup
        if ($user->isAdmin() && !$user->hasTwoFactorEnabled()) {
            if (!$request->routeIs('two-factor.*') && !$request->routeIs('admin.two-factor.*')) {
                return redirect()->route('two-factor.setup')
                    ->with('warning', 'Sebagai admin, Anda wajib mengaktifkan Autentikasi 2 Faktor.');
            }
        }

        return $next($request);
    }
}
