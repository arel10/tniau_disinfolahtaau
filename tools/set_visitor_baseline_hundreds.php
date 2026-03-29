<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\StatisticsCalculationService;

$svc = app(StatisticsCalculationService::class);

$targets = [
    'visitHariIni' => 500,
    'visitKemarin' => 420,
    'visitMingguIni' => 690,
    'visitBulanIni' => 880,
    'visitTahunIni' => 960,
    'visitTotal' => 990,
    'viewsHariIni' => 730,
    'viewsTotal' => 980,
];

foreach ($targets as $period => $target) {
    $svc->setVisitorCount($period, $target, 'set to hundreds range', 'system');
}

echo "Visitor/view counters set to hundreds range.\n";
