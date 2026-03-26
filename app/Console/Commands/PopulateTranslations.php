<?php

namespace App\Console\Commands;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Galeri;
use App\Models\Kategori;
use App\Services\AutoTranslationService;
use Illuminate\Console\Command;

class PopulateTranslations extends Command
{
    protected $signature = 'translate:populate
                            {--locales=en,ar,fr,es,ru,ja : Comma-separated locales to fill}
                            {--model=all : Model to translate: all, berita, event, galeri, kategori}
                            {--force : Re-translate even if translation already exists}';

    protected $description = 'Auto-populate empty locale columns using Google Translate API';

    private array $locales = [];

    public function handle(): int
    {
        $this->locales = explode(',', $this->option('locales'));
        $model = $this->option('model');
        $force = $this->option('force');

        $this->info('Starting auto-translation...');
        $this->info('Locales: ' . implode(', ', $this->locales));

        $map = [
            'berita'   => [Berita::class,   ['judul', 'ringkasan', 'konten']],
            'event'    => [Event::class,    ['nama_kegiatan', 'deskripsi']],
            'galeri'   => [Galeri::class,   ['judul', 'deskripsi']],
            'kategori' => [Kategori::class, ['nama_kategori']],
        ];

        $toRun = ($model === 'all') ? $map : array_intersect_key($map, [$model => true]);

        if (empty($toRun)) {
            $this->error("Unknown model: $model. Use: all, berita, event, galeri, kategori");
            return self::FAILURE;
        }

        foreach ($toRun as $name => [$class, $fields]) {
            $this->translateModel($name, $class, $fields, $force);
        }

        $this->info('Done!');
        return self::SUCCESS;
    }

    private function translateModel(string $name, string $class, array $fields, bool $force): void
    {
        $records = $class::all();
        $this->info("\nTranslating {$class} ({$records->count()} records)...");
        $bar = $this->output->createProgressBar($records->count() * count($this->locales) * count($fields));
        $bar->start();

        foreach ($records as $record) {
            foreach ($this->locales as $locale) {
                foreach ($fields as $field) {
                    $column = $field . '_' . $locale;

                    // Skip if column doesn't exist on model (getter returns null from Eloquent)
                    // Try to read it — if it doesn't throw, it's accessible
                    try {
                        $existing = $record->{$column};
                    } catch (\Exception $e) {
                        $bar->advance();
                        continue;
                    }

                    if (!$force && !empty($existing)) {
                        $bar->advance();
                        continue; // already has a translation
                    }

                    $baseText = $record->{$field} ?? '';
                    if (empty(trim($baseText))) {
                        $bar->advance();
                        continue;
                    }

                    $translated = AutoTranslationService::translate($baseText, $locale);
                    if ($translated) {
                        $record->{$column} = $translated;
                        $record->saveQuietly();
                    }

                    $bar->advance();
                    // Small delay to avoid rate limiting
                    usleep(300000); // 300ms
                }
            }
        }

        $bar->finish();
        $this->newLine();
    }
}
