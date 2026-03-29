<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Berita;
use App\Services\StatisticsCalculationService;

$svc = app(StatisticsCalculationService::class);
$stats = $svc->getDisplayedStats();

echo "=== Displayed Visitor/View Stats ===" . PHP_EOL;
foreach (['visitHariIni','visitKemarin','visitMingguIni','visitBulanIni','visitTahunIni','visitTotal','viewsHariIni','viewsTotal'] as $k) {
    echo $k . ': ' . ($stats[$k] ?? 0) . PHP_EOL;
}

echo PHP_EOL . "=== Sample Berita Views ===" . PHP_EOL;
$items = Berita::query()->orderByDesc('views')->limit(10)->get(['id','judul','views']);
foreach ($items as $b) {
    echo "#{$b->id} | {$b->views} | {$b->judul}" . PHP_EOL;
}
