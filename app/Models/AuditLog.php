<?php

namespace App\Models;

use App\Casts\EncryptedStringCast;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_uuid',
        'action',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'user_agent' => EncryptedStringCast::class,
            'metadata'   => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Scope: filter by action(s).
     */
    public function scopeAction($query, string|array $actions)
    {
        return $query->whereIn('action', (array) $actions);
    }

    /**
     * Related user (by UUID).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
