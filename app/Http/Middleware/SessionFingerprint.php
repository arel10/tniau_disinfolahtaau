<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionFingerprint
{
    /**
     * Validate that the session belongs to the same client.
     * Detects session hijacking by comparing IP + User-Agent fingerprint.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $currentFingerprint = $this->generateFingerprint($request);

        if (session()->has('_fingerprint')) {
            if (session('_fingerprint') !== $currentFingerprint) {
                // Possible session hijacking — destroy session
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Sesi Anda tidak valid. Silakan login kembali.');
            }
        } else {
            session(['_fingerprint' => $currentFingerprint]);
        }

        return $next($request);
    }

    /**
     * Generate a fingerprint from IP + user agent.
     */
    protected function generateFingerprint(Request $request): string
    {
        return hash('sha256', $request->ip() . '|' . $request->userAgent());
    }
}
