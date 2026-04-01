<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fields = ['nama_pejabat', 'pangkat', 'nrp'];
foreach ($fields as $field) {
    $total = App\Models\Struktur::whereNotNull($field)->where($field, '!=', '')->count();
    $encrypted = App\Models\Struktur::query()
        ->whereNotNull($field)
        ->where($field, '!=', '')
        ->where($field, 'like', 'enc::%')
        ->count();
    printf("strukturs.%s total_nonempty=%d encrypted=%d\n", $field, $total, $encrypted);
}
