<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$svc = app(App\Services\StatisticsCalculationService::class);
$stats = $svc->getDisplayedStats();

$keys = [
    'visitHariIni',
    'visitKemarin',
    'visitMingguIni',
    'visitBulanIni',
    'visitTahunIni',
    'visitTotal',
    'viewsHariIni',
    'viewsTotal',
];

foreach ($keys as $k) {
    echo $k . ': ' . ($stats[$k] ?? 0) . PHP_EOL;
}
