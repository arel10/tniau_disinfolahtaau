<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Enforce idle timeout specifically for admin panel sessions.
        $adminIdleMinutes = max((int) config('session.admin_idle_timeout', 10), 1);
        $lastActivityAt = (int) $request->session()->get('admin_last_activity_at', 0);
        if ($lastActivityAt > 0 && (time() - $lastActivityAt) > ($adminIdleMinutes * 60)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Sesi admin berakhir karena tidak aktif lebih dari '.$adminIdleMinutes.' menit.');
        }

        $request->session()->put('admin_last_activity_at', time());

        // Allow both admin and user roles to access admin panel
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}
