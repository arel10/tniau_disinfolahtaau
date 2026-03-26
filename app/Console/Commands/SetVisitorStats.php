<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StatisticsCalculationService;
use App\Models\StatisticsBaseline;

class SetVisitorStats extends Command
{
    protected $signature = 'stats:set {period : Period to set (visitTotal, visitHariIni, etc)} {value : Target display value} {--reason= : Reason for adjustment}';
    protected $description = 'Set visitor statistics to a target value. Treats the value as a starting baseline, not a replacement. New increments will be added on top of this baseline.';

    public function handle(): int
    {
        $period = $this->argument('period');
        $value = (int) $this->argument('value');
        $reason = $this->option('reason') ?? "Manual adjustment via command";

        $statsService = new StatisticsCalculationService();
        $diagnostics = $statsService->getDiagnostics();

        $this->info("=== Statistics Adjustment ===");
        $this->info("Period: $period");
        $this->info("Target value: " . number_format($value));
        $this->info("Reason: $reason");
        $this->newLine();

        $this->info("Current stats:");
        $this->table(['Metric', 'Raw Count', 'Current Baseline', 'Displayed Value'], [
            ['Total Visitors', $diagnostics['raw_counts']['visitTotal'], $diagnostics['baselines']['visitor_total'], $diagnostics['displayed_stats']['visitTotal']],
            ['Daily Visitors', $diagnostics['raw_counts']['visitHariIni'], $diagnostics['baselines']['visitor_harian'], $diagnostics['displayed_stats']['visitHariIni']],
            ['Weekly Visitors', $diagnostics['raw_counts']['visitMingguIni'], $diagnostics['baselines']['visitor_mingguan'], $diagnostics['displayed_stats']['visitMingguIni']],
            ['Monthly Visitors', $diagnostics['raw_counts']['visitBulanIni'], $diagnostics['baselines']['visitor_bulanan'], $diagnostics['displayed_stats']['visitBulanIni']],
            ['Yearly Visitors', $diagnostics['raw_counts']['visitTahunIni'], $diagnostics['baselines']['visitor_tahunan'], $diagnostics['displayed_stats']['visitTahunIni']],
        ]);
        $this->newLine();

        if (!$this->confirm("Do you want to set {$period} to " . number_format($value) . "?")) {
            $this->info("Operation cancelled.");
            return 0;
        }

        // Set the baseline
        $statsService->setVisitorCount($period, $value, $reason, auth()->user()?->name ?? 'command-line');

        $this->info("✓ Baseline adjusted successfully!");
        $this->newLine();

        // Show new values
        $newDiagnostics = $statsService->getDiagnostics();
        $this->info("New stats:");
        $this->table(['Metric', 'Raw Count', 'New Baseline', 'New Displayed Value'], [
            ['Total Visitors', $newDiagnostics['raw_counts']['visitTotal'], $newDiagnostics['baselines']['visitor_total'], $newDiagnostics['displayed_stats']['visitTotal']],
            ['Daily Visitors', $newDiagnostics['raw_counts']['visitHariIni'], $newDiagnostics['baselines']['visitor_harian'], $newDiagnostics['displayed_stats']['visitHariIni']],
            ['Weekly Visitors', $newDiagnostics['raw_counts']['visitMingguIni'], $newDiagnostics['baselines']['visitor_mingguan'], $newDiagnostics['displayed_stats']['visitMingguIni']],
            ['Monthly Visitors', $newDiagnostics['raw_counts']['visitBulanIni'], $newDiagnostics['baselines']['visitor_bulanan'], $newDiagnostics['displayed_stats']['visitBulanIni']],
            ['Yearly Visitors', $newDiagnostics['raw_counts']['visitTahunIni'], $newDiagnostics['baselines']['visitor_tahunan'], $newDiagnostics['displayed_stats']['visitTahunIni']],
        ]);

        $this->info("Note: Real visitor tracking (Visitor table) continues to increment normally.");
        $this->info("The baseline acts as a permanent offset applied to all real counts.");
        $this->info("Example: If you set total to 10,000 and real count is 5, display will be 10,005.");
        $this->info("When real count increments to 6, display becomes 10,006.");

        return 0;
    }
}
