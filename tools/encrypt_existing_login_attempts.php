<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LoginAttempt;

$updated = 0;

LoginAttempt::query()->orderBy('id')->chunkById(500, function ($rows) use (&$updated) {
    foreach ($rows as $row) {
        $raw = $row->getRawOriginal('user_agent');
        if ($raw === null || $raw === '') {
            continue;
        }
        if (is_string($raw) && str_starts_with($raw, 'enc::')) {
            continue;
        }

        $row->user_agent = $row->user_agent;
        $row->save();
        $updated++;
    }
});

echo "Encrypted login_attempts.user_agent updated rows: {$updated}" . PHP_EOL;
