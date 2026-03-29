<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$svc = app(App\Services\StatisticsCalculationService::class);
$periods = [
    'visitHariIni',
    'visitKemarin',
    'visitMingguIni',
    'visitBulanIni',
    'visitTahunIni',
    'visitTotal',
    'viewsHariIni',
    'viewsTotal',
];

foreach ($periods as $period) {
    $svc->setVisitorCount($period, 500, 'set min 500', 'system');
}

echo "Baseline visitor/views diset ke minimum 500.\n";
