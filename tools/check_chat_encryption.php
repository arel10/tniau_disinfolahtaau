<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$total = App\Models\ChatMessage::count();
$encrypted = App\Models\ChatMessage::query()
    ->where('message', 'like', 'enc::%')
    ->count();

printf("chat_messages total=%d encrypted=%d\n", $total, $encrypted);
