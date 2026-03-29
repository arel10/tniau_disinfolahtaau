<?php

namespace App\Models;

use App\Casts\EncryptedStringCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_session_id',
        'sender_type',
        'admin_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'message' => EncryptedStringCast::class,
    ];

    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getSenderLabelAttribute(): string
    {
        if ($this->sender_type === 'admin') {
            return $this->admin?->name ?? 'Admin';
        }

        return $this->chatSession?->visitor_name ?: 'Pengunjung';
    }
}
