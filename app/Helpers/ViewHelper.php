<?php

use App\Services\AutoTranslationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (! function_exists('display_views')) {
    /**
     * Display real stored views (dummy seed + real increments), formatted.
     *
     * @param  mixed  $modelOrCount  Model instance with date/views or an int count
     * @param  mixed  $date          Optional date string or DateTime instance to seed randomness
     * @param  int    $min
     * @param  int    $max
     * @return string                Formatted number
     */
    function display_views($modelOrCount, $date = null, $min = 500, $max = 900)
    {
        $orig = null;
        if (is_numeric($modelOrCount)) {
            $orig = (int) $modelOrCount;
        } elseif (is_object($modelOrCount)) {
            if (isset($modelOrCount->views)) $orig = (int) $modelOrCount->views;
        }

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
