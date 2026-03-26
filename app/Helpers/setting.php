<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('setting')) {
    function setting($key = null, $default = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                DB::table('settings')->updateOrInsert(['key' => $k], ['value' => $v]);
            }
            return true;
        }
        $row = DB::table('settings')->where('key', $key)->first();
        return $row ? $row->value : $default;
    }
}

/**
 * Locale-aware setting helper.
 *
 * Checks for a locale-specific key first (e.g. hero_title_en),
 * then falls back to the base key (hero_title), then to $default.
 *
 * Fallback chain:
 *   1. {key}_{locale}  (e.g. hero_title_en)
 *   2. {key}_en        (English fallback, skipped if locale is already en or id)
 *   3. {key}           (base / Indonesian)
 *   4. $default
 */
if (!function_exists('localized_setting')) {
    function localized_setting($key, $default = null)
    {
        $locale = app()->getLocale();

        // If locale is Indonesian, return the base key directly
        if ($locale === 'id') {
            return setting($key, $default);
        }

        // Try exact locale key (e.g. hero_title_ja)
        $localizedValue = setting($key . '_' . $locale);
        if (!empty($localizedValue)) {
            return $localizedValue;
        }

        // Fallback to English (if not already en)
        if ($locale !== 'en') {
            $enValue = setting($key . '_en');
            if (!empty($enValue)) {
                return $enValue;
            }
        }

        // Ultimate fallback: base (Indonesian) key.
        // For non-Indonesian locales, prefer an explicit $default (lang file translation)
        // over raw Indonesian DB content — so UI strings show in the user's language.
        $baseValue = setting($key);
        if ($default !== null && $baseValue !== null) {
            // If caller supplied a translated default, use it for non-id locale
            return $default;
        }
        return $baseValue !== null ? $baseValue : $default;
    }
}
