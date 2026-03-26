<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\BlockedIp;
use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SecurityAuditService
{
    // ── Audit Logging ────────────────────────────────────────────

    /**
     * Write an audit log entry.
     */
    public static function log(string $action, ?User $user = null, ?Request $request = null, array $metadata = []): AuditLog
    {
        return AuditLog::create([
            'user_uuid'  => $user?->uuid,
            'action'     => $action,
            'ip_address' => $request?->ip(),
            'user_agent' => $request ? substr((string) $request->userAgent(), 0, 500) : null,
            'metadata'   => !empty($metadata) ? $metadata : null,
            'created_at' => now(),
        ]);
    }

    // ── Login Attempt Tracking ───────────────────────────────────

    /**
     * Record a login attempt.
     */
    public static function recordLoginAttempt(Request $request, string $username, bool $successful): void
    {
        LoginAttempt::create([
            'ip_address'  => $request->ip(),
            'username'    => $username,
            'successful'  => $successful,
            'user_agent'  => substr((string) $request->userAgent(), 0, 500),
            'attempted_at' => now(),
        ]);
    }

    /**
     * Count recent failed login attempts for an IP.
     */
    public static function recentFailedAttempts(string $ip, int $minutes = 15): int
    {
        return LoginAttempt::where('ip_address', $ip)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Count recent failed login attempts for a specific username.
     */
    public static function recentFailedAttemptsForUser(string $username, int $minutes = 15): int
    {
        return LoginAttempt::where('username', $username)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    // ── IP Blocking ──────────────────────────────────────────────

    /**
     * Check if an IP is currently blocked.
     */
    public static function isIpBlocked(string $ip): bool
    {
        try {
            $record = BlockedIp::where('ip_address', $ip)->first();
            if (!$record) return false;
            if ($record->blocked_until && $record->blocked_until->isPast()) {
                $record->delete();
                return false;
            }

            return true;
        } catch (QueryException $e) {
            // Fail open to avoid taking down all requests when DB is unavailable.
            Log::warning('Blocked IP lookup skipped: database unavailable', [
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Block an IP for a given duration.
     */
    public static function blockIp(string $ip, int $minutes = 60, string $reason = 'brute_force'): void
    {
        BlockedIp::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason'        => $reason,
                'attempt_count' => BlockedIp::where('ip_address', $ip)->value('attempt_count') + 1 ?: 1,
                'blocked_until' => now()->addMinutes($minutes),
            ]
        );
    }

    /**
     * Evaluate if an IP should be blocked (20+ failed attempts in 15 min).
     */
    public static function evaluateBruteForce(string $ip): void
    {
        $failCount = self::recentFailedAttempts($ip, 15);
        if ($failCount >= 20) {
            self::blockIp($ip, 120, 'brute_force_auto'); // 2 hours
        } elseif ($failCount >= 10) {
            self::blockIp($ip, 30, 'brute_force_warning'); // 30 min
        }
    }

    // ── Account Lock Helpers ─────────────────────────────────────

    /**
     * Lock a user account for $minutes.
     */
    public static function lockAccount(User $user, int $minutes = 15): void
    {
        $user->update(['locked_until' => now()->addMinutes($minutes)]);
    }

    /**
     * Check if account is locked.
     */
    public static function isAccountLocked(User $user): bool
    {
        if (!$user->locked_until) return false;
        if (Carbon::parse($user->locked_until)->isPast()) {
            $user->update(['locked_until' => null, 'failed_login_count' => 0]);
            return false;
        }
        return true;
    }

    /**
     * Increment failed login counter; lock after threshold.
     */
    public static function incrementFailedLogin(User $user): void
    {
        $user->increment('failed_login_count');
        if ($user->failed_login_count >= 5) {
            self::lockAccount($user, 15);
        }
    }

    /**
     * Reset failed login counter (on successful login).
     */
    public static function resetFailedLogin(User $user): void
    {
        $user->update(['failed_login_count' => 0, 'locked_until' => null]);
    }
}
