<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kontak;

$updated = 0;

Kontak::query()->orderBy('id')->chunkById(200, function ($rows) use (&$updated) {
    foreach ($rows as $row) {
        $raw = $row->getRawOriginal('pesan');
        if (is_string($raw) && str_starts_with($raw, 'enc::')) {
            continue;
        }

        $plaintext = $row->pesan; // cast returns plaintext for old values
        $row->pesan = $plaintext; // cast re-encrypts on set
        $row->save();
        $updated++;
    }
});

echo "Encrypted kontak.pesan updated: {$updated}" . PHP_EOL;
