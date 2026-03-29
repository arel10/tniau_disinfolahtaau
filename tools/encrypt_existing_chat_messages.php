<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChatMessage;

$updated = 0;

ChatMessage::query()->orderBy('id')->chunkById(200, function ($rows) use (&$updated) {
    foreach ($rows as $row) {
        $raw = $row->getRawOriginal('message');
        if (is_string($raw) && str_starts_with($raw, 'enc::')) {
            continue;
        }

        $plaintext = $row->message; // cast returns plaintext for old values
        $row->message = $plaintext; // cast re-encrypts on set
        $row->save();
        $updated++;
    }
});

echo "Encrypted chat_messages updated: {$updated}" . PHP_EOL;
