<?php

namespace App\Models;

use App\Casts\EncryptedStringCast;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'username',
        'successful',
        'user_agent',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'user_agent'   => EncryptedStringCast::class,
            'successful'   => 'boolean',
            'attempted_at' => 'datetime',
        ];
    }
}
