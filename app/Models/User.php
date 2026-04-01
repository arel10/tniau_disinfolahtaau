<?php

namespace App\Models;

use App\Casts\EncryptedStringCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ── Boot: auto-generate UUID ────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'uuid',
        'otp_code',
        'otp_expires_at',
        'otp_channel',
        'otp_attempts',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'failed_login_count',
        'locked_until',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'         => 'datetime',
            'otp_expires_at'            => 'datetime',
            'password'                  => 'hashed',
            'phone'                     => EncryptedStringCast::class,
            'two_factor_secret'         => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at'   => 'datetime',
            'locked_until'              => 'datetime',
            'password_changed_at'       => 'datetime',
            'failed_login_count'        => 'integer',
        ];
    }

    // ── 2FA Helpers ─────────────────────────────────────────────

    /**
     * Check if user has two-factor authentication enabled & confirmed.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Generate 8 fresh recovery codes.
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4));
        }
        $this->two_factor_recovery_codes = $codes;
        $this->save();
        return $codes;
    }

    /**
     * Use a recovery code (returns true if valid, false if not).
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->two_factor_recovery_codes ?? [];
        $index = array_search(strtoupper(trim($code)), array_map('strtoupper', $codes));
        if ($index === false) return false;
        unset($codes[$index]);
        $this->two_factor_recovery_codes = array_values($codes);
        $this->save();
        return true;
    }

    // ── Account Lock Helpers ────────────────────────────────────

    /**
     * Check if this account is currently locked.
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    // ── Relationships ───────────────────────────────────────────

    public function beritas()
    {
        return $this->hasMany(Berita::class, 'user_id');
    }

    public function galeris()
    {
        return $this->hasMany(Galeri::class, 'user_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_uuid', 'uuid');
    }

    // ── Role Helpers ────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeRegularUser($query)
    {
        return $query->where('role', 'user');
    }
}
