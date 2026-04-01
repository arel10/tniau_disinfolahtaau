<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$updated = 0;

User::query()->orderBy('id')->chunkById(200, function ($rows) use (&$updated) {
    foreach ($rows as $row) {
        $raw = $row->getRawOriginal('phone');
        if ($raw === null || $raw === '') {
            continue;
        }
        if (is_string($raw) && str_starts_with($raw, 'enc::')) {
            continue;
        }

        $plaintext = $row->phone;
        $row->phone = $plaintext;
        $row->save();
        $updated++;
    }
});

echo "Encrypted users.phone updated: {$updated}" . PHP_EOL;
