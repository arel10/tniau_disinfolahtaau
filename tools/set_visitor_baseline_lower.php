<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\StatisticsCalculationService;

$svc = app(StatisticsCalculationService::class);

$targets = [
    'visitHariIni' => 500,
    'visitKemarin' => 430,
    'visitMingguIni' => 980,
    'visitBulanIni' => 2900,
    'visitTahunIni' => 9800,
    'visitTotal' => 25500,
    'viewsHariIni' => 780,
    'viewsTotal' => 63500,
];

foreach ($targets as $period => $target) {
    $svc->setVisitorCount($period, $target, 'lowered baseline targets', 'system');
}

echo "Visitor/view baselines lowered successfully.\n";
