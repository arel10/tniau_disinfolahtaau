<?php

namespace App\Traits;

use App\Services\AutoTranslationService;

/**
 * Trait HasTranslation
 *
 * Provides locale-aware accessors for translated database columns.
 * Convention: the Indonesian column is the base (e.g. `judul`).
 * Additional columns follow the pattern `{field}_{locale}` (e.g. `judul_en`, `judul_ja`).
 *
 * Fallback chain:  requested locale → en → id (base column).
 *
 * Usage in models:
 *   use HasTranslation;
 *   protected array $translatable = ['judul', 'ringkasan', 'konten'];
 *
 * In blade templates:
 *   $berita->localized_judul
 *   $berita->localized_ringkasan
 *   $berita->localized_konten
 */
trait HasTranslation
{
    protected static array $runtimeTranslationCache = [];

    /**
     * Get the translated value for a given field based on the active locale.
     *
     * Fallback chain:
     *   1. {field}_{locale}  (e.g. judul_en)
     *   2. {field}_en        (English fallback, skipped if locale is already en)
     *   3. {field}           (Indonesian / base column)
     */
    public function getTranslated(string $field): string
    {
        $locale = app()->getLocale();
        $baseValue = trim((string) ($this->{$field} ?? ''));

        // If locale is Indonesian, return the base column directly
        if ($locale === 'id') {
            return $baseValue;
        }

        // Try the exact locale column (e.g. judul_ja)
        $localeColumn = $field . '_' . $locale;
        if (!empty($this->{$localeColumn})) {
            return $this->{$localeColumn};
        }

        // Next attempt: auto-translate base text to active locale and cache result
        if ($baseValue !== '') {
            $supportedLocales = ['en', 'ar', 'fr', 'es', 'ru', 'ja'];
            if (in_array($locale, $supportedLocales, true)) {
                $cacheKey = static::class . ':' . ($this->getKey() ?? 'new') . ':' . $field . ':' . $locale . ':' . md5($baseValue);
                if (isset(self::$runtimeTranslationCache[$cacheKey])) {
                    return self::$runtimeTranslationCache[$cacheKey];
                }

                $translated = AutoTranslationService::translate($baseValue, $locale);
                if (!empty($translated)) {
                    self::$runtimeTranslationCache[$cacheKey] = $translated;

                    // Persist for future requests when locale column exists and is empty
                    try {
                        $attributes = $this->getAttributes();
                        if (array_key_exists($localeColumn, $attributes) && empty($attributes[$localeColumn])) {
                            $this->{$localeColumn} = $translated;
                            $this->saveQuietly();
                        }
                    } catch (\Throwable $e) {
                        // Keep request successful even if DB persistence fails.
                    }

                    return $translated;
                }
            }
        }

        // Fallback to English (if not already en)
        if ($locale !== 'en') {
            $enColumn = $field . '_en';
            if (!empty($this->{$enColumn})) {
                return $this->{$enColumn};
            }
        }

        // Ultimate fallback: base (Indonesian) column
        return $baseValue;
    }

    /**
     * Magic accessor: $model->localized_{field}
     * e.g. $berita->localized_judul
     */
    public function __get($key)
    {
        if (str_starts_with($key, 'localized_')) {
            $field = substr($key, 10); // remove 'localized_' prefix
            if (property_exists($this, 'translatable') && in_array($field, $this->translatable)) {
                return $this->getTranslated($field);
            }
        }

        return parent::__get($key);
    }
}
