<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SecurityAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Max login attempts before throttling.
     */
    protected int $maxAttempts = 5;
    protected int $decayMinutes = 15;

    /**
     * Show the login form.
     */
    public function showLoginForm(Request $request)
    {
        // If IP is blocked, show error
        if (SecurityAuditService::isIpBlocked($request->ip())) {
            return view('auth.login')->with([
                'username' => 'Alamat IP Anda diblokir sementara karena terlalu banyak percobaan login. Coba lagi nanti.',
            ]);
        }

        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'user'])) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request with full security: rate limiting, IP blocking,
     * account locking, audit logging, and 2FA redirect.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:255',
        ], [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        $ip       = $request->ip();
        $username = $request->username;

        // ── 1. Check IP block ────────────────────────────────
        if (SecurityAuditService::isIpBlocked($ip)) {
            return back()->withErrors([
                'username' => 'Alamat IP Anda diblokir sementara. Coba lagi nanti.',
            ])->onlyInput('username');
        }

        // ── 2. Rate limiting (per IP + username combo) ───────
        $throttleKey = Str::lower($username) . '|' . $ip;

        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            SecurityAuditService::recordLoginAttempt($request, $username, false);

            return back()->withErrors([
                'username' => "Terlalu banyak percobaan login. Coba lagi dalam {$minutes} menit.",
            ])->onlyInput('username');
        }

        // ── 3. Check if account exists & is locked ───────────
        $user = User::where('username', $username)->first();

        if ($user && SecurityAuditService::isAccountLocked($user)) {
            SecurityAuditService::recordLoginAttempt($request, $username, false);
            return back()->withErrors([
                'username' => 'Akun Anda terkunci sementara karena terlalu banyak percobaan login gagal. Coba lagi nanti.',
            ])->onlyInput('username');
        }

        // ── 4. Attempt authentication ────────────────────────
        $credentials = [
            'username' => $username,
            'password' => $request->password,
        ];

        $remember = $request->filled('remember');

        if (!Auth::attempt($credentials, $remember)) {
            // Failed login
            RateLimiter::hit($throttleKey, $this->decayMinutes * 60);
            SecurityAuditService::recordLoginAttempt($request, $username, false);

            // Increment user's failed login count
            if ($user) {
                SecurityAuditService::incrementFailedLogin($user);
            }

            // Evaluate brute force (IP-based)
            SecurityAuditService::evaluateBruteForce($ip);

            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->onlyInput('username');
        }

        // ── 5. Login successful ──────────────────────────────
        $user = Auth::user();

        // Check role is allowed
        if (!in_array($user->role, ['admin', 'user'])) {
            Auth::logout();
            SecurityAuditService::recordLoginAttempt($request, $username, false);
            return back()->withErrors([
                'username' => 'Anda tidak memiliki akses ke dashboard.',
            ])->onlyInput('username');
        }

        // Reset failed login counter
        SecurityAuditService::resetFailedLogin($user);

        // Clear rate limiter
        RateLimiter::clear($throttleKey);

        // Regenerate session (session fixation protection)
        $request->session()->regenerate();

        // Reset admin idle timer on fresh login so stale values from an old
        // session do not trigger immediate logout on the next admin page.
        $request->session()->forget('admin_last_activity_at');
        $request->session()->put('admin_last_activity_at', time());

        // Store fingerprint for session hijacking detection
        session(['_fingerprint' => hash('sha256', $ip . '|' . $request->userAgent())]);

        // Record successful login
        SecurityAuditService::recordLoginAttempt($request, $username, true);
        SecurityAuditService::log('login', $user, $request);

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Selamat datang, ' . ($user->username ?: $user->name) . '!');
    }

    /**
     * Log the user out with audit logging.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            SecurityAuditService::log('logout', $user, $request);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}
