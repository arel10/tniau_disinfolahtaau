<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

class EncryptedStringCast implements CastsAttributes
{
    private const PREFIX = 'enc::';

    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (!is_string($value) || !str_starts_with($value, self::PREFIX)) {
            // Backward compatibility for old plaintext rows.
            return $value;
        }

        $payload = substr($value, strlen(self::PREFIX));

        try {
            return Crypt::decryptString($payload);
        } catch (\Throwable $e) {
            // If value is corrupted, return raw value to avoid fatal read errors.
            return $value;
        }
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (is_string($value) && str_starts_with($value, self::PREFIX)) {
            // Already encrypted by this cast.
            return $value;
        }

        return self::PREFIX . Crypt::encryptString((string) $value);
    }
}
