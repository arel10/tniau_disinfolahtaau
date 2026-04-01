<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$total = App\Models\Kontak::count();
$encrypted = App\Models\Kontak::query()
    ->where('pesan', 'like', 'enc::%')
    ->count();

printf("kontak total=%d encrypted=%d\n", $total, $encrypted);
