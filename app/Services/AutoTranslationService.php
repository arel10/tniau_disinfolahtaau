<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AutoTranslationService
 *
 * Uses the unofficial Google Translate API (same backend as the GT widget)
 * to translate text server-side. Results should be cached in DB locale columns.
 *
 * Target API: https://translate.googleapis.com/translate_a/single
 */
class AutoTranslationService
{
    /**
     * Translate a single string from Indonesian to the target locale.
     *
     * @param  string $text   The source text to translate (in Indonesian)
     * @param  string $target Target locale code (en, ar, fr, es, ru, ja)
     * @return string|null    Translated text, or null on failure
     */
    public static function translate(string $text, string $target): ?string
    {
        if (empty(trim($text)) || $target === 'id') {
            return null;
        }

        // Google Translate locale mapping (API uses slightly different codes)
        $localeMap = [
            'en' => 'en', 'ar' => 'ar', 'fr' => 'fr',
            'es' => 'es', 'ru' => 'ru', 'ja' => 'ja',
        ];
        $gtLocale = $localeMap[$target] ?? $target;

        try {
            $response = Http::timeout(10)
                ->withoutVerifying()   // Windows PHP often lacks CA bundle; endpoint is a public Google API
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->get('https://translate.googleapis.com/translate_a/single', [
                    'client' => 'gtx',
                    'sl'     => 'id',
                    'tl'     => $gtLocale,
                    'dt'     => 't',
                    'q'      => $text,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Response format: [[["translated","original",null,null,null],...],...]
                if (!empty($data[0]) && is_array($data[0])) {
                    $translated = '';
                    foreach ($data[0] as $segment) {
                        if (isset($segment[0])) {
                            $translated .= $segment[0];
                        }
                    }
                    return trim($translated) ?: null;
                }
            }
        } catch (\Exception $e) {
            Log::warning('AutoTranslationService: translation failed', [
                'target' => $target,
                'error'  => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Translate a field on an Eloquent model and persist it.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $field      Base field name (e.g. 'judul')
     * @param  string $locale     Target locale (e.g. 'en')
     * @return bool               True if translation was saved
     */
    public static function translateAndSave($model, string $field, string $locale): bool
    {
        $column = $field . '_' . $locale;
        $baseText = $model->{$field} ?? '';

        if (empty($baseText)) {
            return false;
        }

        $translated = self::translate($baseText, $locale);
        if ($translated) {
            $model->{$column} = $translated;
            $model->saveQuietly(); // avoid triggering observers recursively
            return true;
        }

        return false;
    }
}
