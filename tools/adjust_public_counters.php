<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Berita;
use App\Services\StatisticsCalculationService;
use Illuminate\Support\Facades\DB;

$svc = app(StatisticsCalculationService::class);

// Visitor counters: make them realistic per period (not all equal).
$targets = [
    'visitHariIni' => 500,
    'visitKemarin' => 460,
    'visitMingguIni' => 1850,
    'visitBulanIni' => 7600,
    'visitTahunIni' => 32800,
    'visitTotal' => 125000,
    'viewsHariIni' => 980,
    'viewsTotal' => 198000,
];

foreach ($targets as $period => $target) {
    $svc->setVisitorCount($period, $target, 'manual tune to realistic distribution', 'system');
}

// News views: raise low counts so existing dummy seed feels realistic.
$raisedLowViews = Berita::query()
    ->where('views', '<', 500)
    ->update(['views' => DB::raw('views + 500')]);

// Also add a light global boost so all posts feel active but keep relative ranking.
$globalBoost = 35;
$boostedAll = Berita::query()->increment('views', $globalBoost);

echo "Counters adjusted successfully." . PHP_EOL;
echo "Low-view berita raised: {$raisedLowViews}" . PHP_EOL;
echo "All berita boosted by: {$globalBoost}" . PHP_EOL;
echo "Total berita affected by global boost: {$boostedAll}" . PHP_EOL;
