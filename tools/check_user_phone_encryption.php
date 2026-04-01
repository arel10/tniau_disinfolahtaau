<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$total = App\Models\User::whereNotNull('phone')->where('phone', '!=', '')->count();
$encrypted = App\Models\User::query()
    ->whereNotNull('phone')
    ->where('phone', '!=', '')
    ->where('phone', 'like', 'enc::%')
    ->count();

printf("users.phone total_nonempty=%d encrypted=%d\n", $total, $encrypted);
