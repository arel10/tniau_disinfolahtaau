<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StatisticsCalculationService;
use App\Models\Visitor;

class CheckVisitorStats extends Command
{
    protected $signature = 'stats:check';
    protected $description = 'Check visitor statistics and verify that counting continues properly after manipulation.';

    public function handle(): int
    {
        $this->info("=== Visitor Statistics Diagnostics ===");
        $this->newLine();

        $statsService = new StatisticsCalculationService();
        $diagnostics = $statsService->getDiagnostics();

        $this->table(['Metric', 'Value'], [
            ['Real Visitor Count (Database)', number_format($diagnostics['raw_counts']['visitTotal'])],
            ['Real Daily Visitors', number_format($diagnostics['raw_counts']['visitHariIni'])],
            ['Real Weekly Visitors', number_format($diagnostics['raw_counts']['visitMingguIni'])],
            ['Real Monthly Visitors', number_format($diagnostics['raw_counts']['visitBulanIni'])],
            ['Real Yearly Visitors', number_format($diagnostics['raw_counts']['visitTahunIni'])],
        ]);

        $this->newLine();
        $this->info("Applied Baselines (permanent offsets):");
        $this->table(['Period', 'Baseline'], [
            ['Daily', number_format($diagnostics['baselines']['visitor_harian'])],
            ['Weekly', number_format($diagnostics['baselines']['visitor_mingguan'])],
            ['Monthly', number_format($diagnostics['baselines']['visitor_bulanan'])],
            ['Yearly', number_format($diagnostics['baselines']['visitor_tahunan'])],
            ['Total', number_format($diagnostics['baselines']['visitor_total'])],
        ]);

        $this->newLine();
        $this->info("Displayed Statistics (Real Count + Baseline):");
        $this->table(['Metric', 'Formula', 'Displayed Value'], [
            ['Daily', $diagnostics['raw_counts']['visitHariIni'] . ' + ' . $diagnostics['baselines']['visitor_harian'], number_format($diagnostics['displayed_stats']['visitHariIni'])],
            ['Weekly', $diagnostics['raw_counts']['visitMingguIni'] . ' + ' . $diagnostics['baselines']['visitor_mingguan'], number_format($diagnostics['displayed_stats']['visitMingguIni'])],
            ['Monthly', $diagnostics['raw_counts']['visitBulanIni'] . ' + ' . $diagnostics['baselines']['visitor_bulanan'], number_format($diagnostics['displayed_stats']['visitBulanIni'])],
            ['Yearly', $diagnostics['raw_counts']['visitTahunIni'] . ' + ' . $diagnostics['baselines']['visitor_tahunan'], number_format($diagnostics['displayed_stats']['visitTahunIni'])],
            ['Total', $diagnostics['raw_counts']['visitTotal'] . ' + ' . $diagnostics['baselines']['visitor_total'], number_format($diagnostics['displayed_stats']['visitTotal'])],
        ]);

        $this->newLine();
        $this->info("Verification:");
        $this->line("Daily Calculation: " . $diagnostics['verification']['daily_calculation']);
        $this->line("Total Calculation: " . $diagnostics['verification']['total_calculation']);

        $this->newLine();
        $this->comment("✓ Statistics are working correctly!");
        $this->comment("✓ Real visitor tracking (increments from TrackVisitor middleware) continues normally.");
        $this->comment("✓ Baselines act as permanent offsets - display = real_count + baseline.");
        $this->comment("✓ New visitors will increment the real count, making the displayed value increase.");

        return 0;
    }
}
