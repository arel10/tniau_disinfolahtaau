<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatisticsBaseline extends Model
{
    protected $table = 'statistics_baselines';

    protected $fillable = [
        'key',
        'baseline_value',
        'notes',
        'adjusted_at',
        'adjusted_by',
    ];

    protected $casts = [
        'baseline_value' => 'integer',
        'adjusted_at' => 'datetime',
    ];

    /**
     * Get or create baseline for a key, with default value of 0
     */
    public static function getBaseline(string $key): int
    {
        return self::where('key', $key)->first()?->baseline_value ?? 0;
    }

    /**
     * Set baseline for a key (treat manually-set value as a starting point, not a replacement)
     * This ensures that all future increments from this point forward will be added to this baseline
     */
    public static function setBaseline(string $key, int $value, ?string $notes = null, ?string $adjustedBy = null): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'baseline_value' => $value,
                'notes' => $notes,
                'adjusted_at' => now(),
                'adjusted_by' => $adjustedBy,
            ]
        );
    }

    /**
     * Increment baseline by a value
     */
    public static function incrementBaseline(string $key, int $amount = 1): void
    {
        $record = self::where('key', $key)->first();
        if ($record) {
            $record->increment('baseline_value', $amount);
        } else {
            self::create([
                'key' => $key,
                'baseline_value' => $amount,
                'adjusted_at' => now(),
            ]);
        }
    }
}
