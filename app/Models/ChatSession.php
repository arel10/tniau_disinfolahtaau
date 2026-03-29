<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ChatSession extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CLOSED = 'closed';

    public const INACTIVE_AFTER_SECONDS = 120;
    public const EXPIRE_AFTER_SECONDS = 1800;
    public const VISITOR_HIDE_AFTER_SECONDS = 120;
    public const ADMIN_REPLY_WAIT_SECONDS = 120;

    protected $fillable = [
        'visitor_token',
        'visitor_name',
        'status',
        'hidden_for_visitor',
        'admin_read_at',
        'started_at',
        'ended_at',
        'last_activity_at',
        'last_seen_by_visitor_at',
        'last_reply_by_admin_at',
        'visitor_left_page_at',
    ];

    protected $casts = [
        'hidden_for_visitor' => 'boolean',
        'admin_read_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'last_seen_by_visitor_at' => 'datetime',
        'last_reply_by_admin_at' => 'datetime',
        'visitor_left_page_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->visitor_name ?: 'Pengunjung';
    }

    public static function labelForStatus(string $status): string
    {
        return match ($status) {
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
            self::STATUS_EXPIRED => 'Kedaluwarsa',
            self::STATUS_CLOSED => 'Ditutup',
            default => ucfirst($status),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::labelForStatus($this->status);
    }

    public function unreadVisitorMessages()
    {
        return $this->hasMany(ChatMessage::class)
            ->where('sender_type', 'visitor')
            ->where('is_read', false);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isFinished(): bool
    {
        return in_array($this->status, [self::STATUS_EXPIRED, self::STATUS_CLOSED], true);
    }

    public function canAdminReply(): bool
    {
        return !$this->isFinished();
    }

    public function hideForVisitor(): void
    {
        $data = [];
        if ($this->hasColumn('hidden_for_visitor')) {
            $data['hidden_for_visitor'] = true;
        }
        if ($this->hasColumn('visitor_left_page_at')) {
            $data['visitor_left_page_at'] = now();
        }

        if (!empty($data)) {
            $this->forceFill($data)->save();
        }
    }

    public function showForVisitor(): void
    {
        $data = [];
        if ($this->hasColumn('hidden_for_visitor')) {
            $data['hidden_for_visitor'] = false;
        }
        if ($this->hasColumn('visitor_left_page_at')) {
            $data['visitor_left_page_at'] = null;
        }

        if (!empty($data)) {
            $this->forceFill($data)->save();
        }
    }

    public function isVisibleToVisitor(): bool
    {
        if ($this->hasColumn('hidden_for_visitor') && $this->hidden_for_visitor) {
            return false;
        }

        if ($this->isFinished()) {
            return false;
        }

        return true;
    }

    public function markVisitorLeft(): void
    {
        $data = [];
        if ($this->hasColumn('visitor_left_page_at')) {
            $data['visitor_left_page_at'] = now();
        }
        if ($this->hasColumn('status') && $this->status === self::STATUS_ACTIVE) {
            $data['status'] = self::STATUS_INACTIVE;
        }

        if (!empty($data)) {
            $this->forceFill($data)->save();
        }
    }

    public function recordVisitorActivity(): void
    {
        $data = [];
        if ($this->hasColumn('last_seen_by_visitor_at')) {
            $data['last_seen_by_visitor_at'] = now();
        }
        if ($this->hasColumn('last_activity_at')) {
            $data['last_activity_at'] = now();
        }
        if ($this->hasColumn('hidden_for_visitor')) {
            $data['hidden_for_visitor'] = false;
        }
        if ($this->hasColumn('visitor_left_page_at')) {
            $data['visitor_left_page_at'] = null;
        }

        if (!empty($data)) {
            $this->forceFill($data)->save();
        }
    }

    public function recordAdminReply(): void
    {
        $data = [];
        if ($this->hasColumn('last_reply_by_admin_at')) {
            $data['last_reply_by_admin_at'] = now();
        }
        if ($this->hasColumn('last_activity_at')) {
            $data['last_activity_at'] = now();
        }
        if ($this->hasColumn('status')) {
            $data['status'] = self::STATUS_ACTIVE;
        }
        if ($this->hasColumn('hidden_for_visitor')) {
            $data['hidden_for_visitor'] = false;
        }
        if ($this->hasColumn('visitor_left_page_at')) {
            $data['visitor_left_page_at'] = null;
        }

        if (!empty($data)) {
            $this->forceFill($data)->save();
        }
    }

    public function hasVisitorReplyAfterLatestAdminReply(): bool
    {
        if (!$this->last_reply_by_admin_at) {
            return true;
        }

        return $this->messages()
            ->where('sender_type', 'visitor')
            ->where('created_at', '>', $this->last_reply_by_admin_at)
            ->exists();
    }

    public function markAsReadByAdmin(): void
    {
        $this->messages()
            ->where('sender_type', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->forceFill(['admin_read_at' => now()])->save();
    }

    public function touchActivity(string $status = self::STATUS_ACTIVE): void
    {
        $data = ['status' => $status];
        if ($this->hasColumn('last_activity_at')) {
            $data['last_activity_at'] = now();
        }
        if ($this->hasColumn('ended_at')) {
            $data['ended_at'] = null;
        }

        $this->forceFill($data)->save();
    }

    private function hasColumn(string $column): bool
    {
        try {
            return Schema::hasColumn($this->getTable(), $column);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function applyLifecycleTransitions(): void
    {
        if ($this->isFinished() || !$this->last_activity_at) {
            return;
        }

        $idleSeconds = now()->diffInSeconds($this->last_activity_at);

        if ($idleSeconds >= self::EXPIRE_AFTER_SECONDS) {
            $this->forceFill([
                'status' => self::STATUS_EXPIRED,
                'ended_at' => $this->ended_at ?? now(),
            ])->save();
            return;
        }

        if ($idleSeconds >= self::INACTIVE_AFTER_SECONDS && $this->status === self::STATUS_ACTIVE) {
            $this->forceFill(['status' => self::STATUS_INACTIVE])->save();
        }

        try {
            if ($this->hasColumn('visitor_left_page_at') && $this->visitor_left_page_at) {
                $sinceLeft = now()->diffInSeconds($this->visitor_left_page_at);
                if ($sinceLeft >= self::VISITOR_HIDE_AFTER_SECONDS && !($this->hidden_for_visitor ?? false)) {
                    $this->hideForVisitor();
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            if ($this->last_reply_by_admin_at && $this->last_seen_by_visitor_at) {
                $sinceSeen = now()->diffInSeconds($this->last_seen_by_visitor_at);
                if (
                    $sinceSeen >= self::VISITOR_HIDE_AFTER_SECONDS
                    && $this->last_seen_by_visitor_at->greaterThan($this->last_reply_by_admin_at)
                    && !($this->hidden_for_visitor ?? false)
                ) {
                    $this->hideForVisitor();
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            if ($this->last_reply_by_admin_at && !$this->hasVisitorReplyAfterLatestAdminReply()) {
                $sinceAdminReply = now()->diffInSeconds($this->last_reply_by_admin_at);
                if ($sinceAdminReply >= self::ADMIN_REPLY_WAIT_SECONDS) {
                    $data = ['status' => self::STATUS_CLOSED];
                    if ($this->hasColumn('ended_at')) {
                        $data['ended_at'] = $this->ended_at ?? now();
                    }
                    if ($this->hasColumn('hidden_for_visitor')) {
                        $data['hidden_for_visitor'] = true;
                    }
                    $this->forceFill($data)->save();
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function scopeOpenForVisitor($query, string $token)
    {
        return $query
            ->where('visitor_token', $token)
            ->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_INACTIVE]);
    }

    public function scopeUnread($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('admin_read_at')
                ->orWhereHas('messages', function ($mq) {
                    $mq->where('sender_type', 'visitor')->where('is_read', false);
                });

            try {
                if (Schema::hasColumn((new self())->getTable(), 'hidden_for_visitor')) {
                    $q->orWhere('hidden_for_visitor', true);
                }
            } catch (\Throwable $e) {
                // ignore schema issues and keep previous behavior
            }
        });
    }

    public function scopeWithMessages($query)
    {
        return $query->whereHas('messages');
    }
}
