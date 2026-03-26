<?php

use App\Services\AutoTranslationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (! function_exists('display_views')) {
    /**
     * Return a deterministic "random" view count between $min and $max based on a date.
     * If no date is available, falls back to the original count (formatted).
     *
     * @param  mixed  $modelOrCount  Model instance with date/views or an int count
     * @param  mixed  $date          Optional date string or DateTime instance to seed randomness
     * @param  int    $min
     * @param  int    $max
     * @return string                Formatted number
     */
    function display_views($modelOrCount, $date = null, $min = 500, $max = 900)
    {
        // determine original value and extract date from model if provided
        $orig = null;
        if (is_numeric($modelOrCount)) {
            $orig = (int) $modelOrCount;
        } elseif (is_object($modelOrCount)) {
            if (isset($modelOrCount->views)) $orig = (int) $modelOrCount->views;
            // attempt various date fields commonly used
            if (! $date) {
                if (! empty($modelOrCount->tanggal)) $date = $modelOrCount->tanggal;
                elseif (! empty($modelOrCount->published_at)) $date = $modelOrCount->published_at;
                elseif (! empty($modelOrCount->created_at)) $date = $modelOrCount->created_at;
            }
        }

        // Normalize date to string
        $dateStr = null;
        if ($date) {
            if (is_string($date)) {
                $dateStr = $date;
            } elseif (is_object($date) && method_exists($date, 'format')) {
                try { $dateStr = $date->format('Y-m-d'); } catch (\Throwable $e) { $dateStr = (string) $date; }
            } else {
                $dateStr = (string) $date;
            }
        }

        // If we have a date, derive a deterministic base value in range
        if ($dateStr) {
            $seed = (int) crc32($dateStr . (is_object($modelOrCount) && isset($modelOrCount->id) ? (string)$modelOrCount->id : ''));
            // Ensure positive
            $seed = $seed < 0 ? ~$seed + 1 : $seed;
            $range = $max - $min + 1;
            $deterministic = $min + ($seed % $range);

            // If we have an original DB value, let real visits be reflected:
            // show the larger of the deterministic base and a slightly-offset real count.
            $origVal = $orig ?? 0;
            $smallOffset = max(1, (int) round($deterministic * 0.01));
            $fromDb = $origVal + $smallOffset;
            $displayVal = max($deterministic, $fromDb);

            return number_format($displayVal);
        }

        // fallback to original value (formatted) or a safe default
        $fallback = $orig ?? 0;
        return number_format($fallback);
    }
}

if (! function_exists('adjust_visitor_stats')) {
    /**
     * DEPRECATED: This function is replaced by StatisticsCalculationService
     * 
     * Keep for backward compatibility, but use StatisticsCalculationService for new code.
     * 
     * Old behavior: Added pseudo-random offsets to stats (problematic for incrementing)
     * New behavior: Use explicit baselines stored in database
     *
     * @param array $stats
     * @param int $baseline - DEPRECATED, use StatisticsCalculationService instead
     * @return array
     */
    function adjust_visitor_stats(array $stats, $baseline = 10000)
    {
        // For backward compatibility, simply return stats as-is
        // Real baseline adjustment now happens in StatisticsCalculationService
        // which uses database-persisted baselines instead of pseudo-random offsets
        
        return $stats ?? [];
    }
}

if (! function_exists('localized_text')) {
    /**
     * Translate dynamic DB text at render time for the active locale.
     * Uses cache so repeated strings do not trigger repeated API calls.
     */
    function localized_text($text): string
    {
        $value = trim((string) $text);
        if ($value === '') {
            return '';
        }

        $locale = app()->getLocale();
        if ($locale === 'id') {
            return $value;
        }

        $cacheKey = 'dynamic_text_translation:' . $locale . ':' . md5($value);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($value, $locale) {
            return AutoTranslationService::translate($value, $locale) ?: $value;
        });
    }
}
