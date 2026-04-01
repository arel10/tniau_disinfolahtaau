<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$total = App\Models\LoginAttempt::whereNotNull('user_agent')->where('user_agent', '!=', '')->count();
$encrypted = App\Models\LoginAttempt::query()
    ->whereNotNull('user_agent')
    ->where('user_agent', '!=', '')
    ->where('user_agent', 'like', 'enc::%')
    ->count();

printf("login_attempts.user_agent total_nonempty=%d encrypted=%d\n", $total, $encrypted);
