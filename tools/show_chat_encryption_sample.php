<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$row = App\Models\ChatMessage::query()->first();
if (!$row) {
    echo "No chat messages found.\n";
    exit(0);
}

$raw = $row->getRawOriginal('message');
$decrypted = $row->message;

echo "ID: {$row->id}\n";
echo "RAW (DB): {$raw}\n";
echo "DECRYPTED (App): {$decrypted}\n";
