  <?php

namespace App\Jobs;

use App\Services\AutoTranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateModelContent implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $uniqueFor = 60; // lock expires after 60 seconds if job is lost

    /**
     * @param  string $modelClass  Fully-qualified model class name
     * @param  int    $modelId     Primary key of the record
     * @param  array  $fields      Base field names to translate (e.g. ['judul', 'ringkasan'])
     * @param  array  $locales     Target locales (e.g. ['en', 'ar', 'fr', 'es', 'ru', 'ja'])
     */
    public function __construct(
        public readonly string $modelClass,
        public readonly int    $modelId,
        public readonly array  $fields,
        public readonly array  $locales = ['en', 'ar', 'fr', 'es', 'ru', 'ja']
    ) {}

    /**
     * Unique job key: one pending job per model+id to prevent translation storms
     * when a record is saved multiple times in quick succession.
     */
    public function uniqueId(): string
    {
        return md5($this->modelClass) . '_' . $this->modelId;
    }

    /**
     * Exponential backoff: wait 30s, then 120s before each retry attempt.
     */
    public function backoff(): array
    {
        return [30, 120];
    }

    /**
     * Log a warning when the job exhausts all retries.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('TranslateModelContent job failed permanently', [
            'model'   => $this->modelClass,
            'id'      => $this->modelId,
            'fields'  => $this->fields,
            'locales' => $this->locales,
            'error'   => $exception->getMessage(),
        ]);
    }

    public function handle(): void
    {
        $record = $this->modelClass::find($this->modelId);
        if (!$record) {
            return;
        }

        $changed = false;
        foreach ($this->locales as $locale) {
            foreach ($this->fields as $field) {
                $column = $field . '_' . $locale;
                $baseText = $record->{$field} ?? '';

                if (empty(trim($baseText))) {
                    continue;
                }

                // Skip if translation already present
                try {
                    if (!empty($record->{$column})) {
                        continue;
                    }
                } catch (\Exception $e) {
                    continue;
                }

                $translated = AutoTranslationService::translate($baseText, $locale);
                if ($translated) {
                    $record->{$column} = $translated;
                    $changed = true;
                }

                usleep(200000); // 200ms between requests to avoid rate limiting
            }
        }

        if ($changed) {
            $record->saveQuietly();
        }
    }
}
