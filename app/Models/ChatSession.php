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
    public const VISITOR_HIDE_AFTER_SECONDS = 1200;

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
        return $this->hasMany(ChatMessage::class)->where('sender_type', 'visitor')->where('is_read', false);
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
        // Admin may reply to sessions that are not finished (active or inactive),
        // but cannot reply to expired or closed sessions.
        return !$this->isFinished();
    }

    /**
     * Hide chat from visitor side (e.g., when visitor is idle or left page)
     * Marks it as hidden but does NOT delete the record from database
     */
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

    /**
     * Show chat to visitor (e.g., when admin replies after it was hidden)
     */
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

    /**
     * Check if chat should be visible to visitor
     * Returns false if hidden due to inactivity, page leaving, or session finished
     * (No side effects; check only)
     */
    public function isVisibleToVisitor(): bool
    {
        // If explicitly marked as hidden, it's not visible
        if ($this->hasColumn('hidden_for_visitor') && $this->hidden_for_visitor) {
            return false;
        }

        // If session is finished (closed/expired), not visible
        if ($this->isFinished()) {
            return false;
        }

        return true;
    }

    /**
     * Mark that visitor left page/browser; hide will be applied after timeout.
     */
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

    /**
     * Track that visitor is currently active on the page with chat open
     * This records their last activity and unhides the chat if needed
     */
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
            $data['hidden_for_visitor'] = false; // Unhide when visitor is active
        }
        if ($this->hasColumn('visitor_left_page_at')) {
            $data['visitor_left_page_at'] = null;
        }

        if (!empty($data)) {
            $this->forceFill($data)->save();
        }
    }

    /**
     * Track when admin replies to this chat
     * This unhides the chat on visitor side so they see the reply
     */
    public function recordAdminReply(): void
    {
        $data = [];
        if ($this->hasColumn('last_reply_by_admin_at')) {
            $data['last_reply_by_admin_at'] = now();
        }
        if ($this->hasColumn('last_activity_at')) {
            $data['last_activity_at'] = now();
        }

        if (!empty($data)) {
            $this->forceFill($data)->save();
        }
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

    /**
     * Helper to check whether a DB column exists on the chat_sessions table.
     */
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

        // If visitor explicitly left page/browser, auto-hide only after >20 minutes.
        try {
            if ($this->hasColumn('visitor_left_page_at') && $this->visitor_left_page_at) {
                $sinceLeft = now()->diffInSeconds($this->visitor_left_page_at);
                if ($sinceLeft >= self::VISITOR_HIDE_AFTER_SECONDS) {
                    if (!($this->hidden_for_visitor ?? false)) {
                        $this->hideForVisitor();
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // If admin replied and visitor had seen the reply, but visitor has been away
        // for more than VISITOR_HIDE_AFTER_SECONDS, hide the session for visitor.
        // This covers the case: admin replies -> visitor opens and sees reply -> visitor leaves
        // without replying; after the timeout we should hide the chat from visitor view.
        try {
            if ($this->last_reply_by_admin_at && $this->last_seen_by_visitor_at) {
                $sinceSeen = now()->diffInSeconds($this->last_seen_by_visitor_at);
                // Only hide if visitor saw the admin reply (seen timestamp after reply)
                if ($sinceSeen >= self::VISITOR_HIDE_AFTER_SECONDS && $this->last_seen_by_visitor_at->greaterThan($this->last_reply_by_admin_at)) {
                    if (!($this->hidden_for_visitor ?? false)) {
                        $this->hideForVisitor();
                    }
                }
            }
        } catch (\Throwable $e) {
            // If columns don't exist or comparison fails, ignore and continue
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
        return $query->where(function ($q) use ($query) {
            $q->whereNull('admin_read_at')
                ->orWhereHas('messages', function ($mq) {
                    $mq->where('sender_type', 'visitor')->where('is_read', false);
                });

            // If the schema has hidden_for_visitor flag, consider hidden sessions as unread
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
