<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fields = ['user_agent'];
foreach ($fields as $field) {
    $total = App\Models\AuditLog::whereNotNull($field)->where($field, '!=', '')->count();
    $encrypted = App\Models\AuditLog::query()
        ->whereNotNull($field)
        ->where($field, '!=', '')
        ->where($field, 'like', 'enc::%')
        ->count();
    printf("audit_logs.%s total_nonempty=%d encrypted=%d\n", $field, $total, $encrypted);
}
