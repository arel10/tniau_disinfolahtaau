<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Struktur;

$updated = 0;

Struktur::query()->orderBy('id')->chunkById(200, function ($rows) use (&$updated) {
    foreach ($rows as $row) {
        $changed = false;

        foreach (['nama_pejabat', 'pangkat', 'nrp'] as $field) {
            $raw = $row->getRawOriginal($field);
            if ($raw === null || $raw === '') {
                continue;
            }
            if (is_string($raw) && str_starts_with($raw, 'enc::')) {
                continue;
            }

            $row->{$field} = $row->{$field};
            $changed = true;
        }

        if ($changed) {
            $row->save();
            $updated++;
        }
    }
});

echo "Encrypted strukturs (nama_pejabat/pangkat/nrp) updated rows: {$updated}" . PHP_EOL;
