<?php

namespace App\Services;

use App\Models\Visitor;
use App\Models\StatisticsBaseline;
use Carbon\Carbon;

/**
 * Service for calculating visitor statistics with proper baseline handling.
 * 
 * This service ensures that:
 * 1. Real visitor tracking continues normally via the TrackVisitor middleware
 * 2. When values are manually manipulated (seed), they are stored as baselines
 * 3. All future increments are added to the baseline, not replacing it
 * 4. Display value = real_count + baseline
 */
class StatisticsCalculationService
{
    /**
     * Get raw visitor statistics from database (actual incremented values)
     */
    public function getRawVerificationStats(): array
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $startOfWeek = $today->copy()->startOfWeek();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfYear = $today->copy()->startOfYear();

        $stats = Visitor::query()
            ->selectRaw(
                "SUM(CASE WHEN visited_at = ? THEN 1 ELSE 0 END) as visitHariIni,
                 SUM(CASE WHEN visited_at = ? THEN 1 ELSE 0 END) as visitKemarin,
                 SUM(CASE WHEN visited_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as visitMingguIni,
                 SUM(CASE WHEN visited_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as visitBulanIni,
                 SUM(CASE WHEN visited_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as visitTahunIni,
                 COUNT(*) as visitTotal",
                [
                    $today->toDateString(),
                    $yesterday->toDateString(),
                    $startOfWeek->toDateString(),
                    $today->toDateString(),
                    $startOfMonth->toDateString(),
                    $today->toDateString(),
                    $startOfYear->toDateString(),
                    $today->toDateString(),
                ]
            )
            ->first();

        return [
            'visitHariIni' => (int) ($stats->visitHariIni ?? 0),
            'visitKemarin' => (int) ($stats->visitKemarin ?? 0),
            'visitMingguIni' => (int) ($stats->visitMingguIni ?? 0),
            'visitBulanIni' => (int) ($stats->visitBulanIni ?? 0),
            'visitTahunIni' => (int) ($stats->visitTahunIni ?? 0),
            'visitTotal' => (int) ($stats->visitTotal ?? 0),
        ];
    }

    /**
     * Calculate displayed statistics by adding baselines to real counts
     * This treats manually-set values as starting points (baselines) that persist
     */
    public function getDisplayedStats(): array
    {
        $raw = $this->getRawVerificationStats();

        // Get baselines from database (these are the manually-set starting points)
        $baseline = StatisticsBaseline::getBaseline('visitor_harian');
        $baselineKemarin = StatisticsBaseline::getBaseline('visitor_kemarin');
        $baselineMingguan = StatisticsBaseline::getBaseline('visitor_mingguan');
        $baselineBulanan = StatisticsBaseline::getBaseline('visitor_bulanan');
        $baselineTahunan = StatisticsBaseline::getBaseline('visitor_tahunan');
        $baselineTotal = StatisticsBaseline::getBaseline('visitor_total');
        $baselineViewsHarian = StatisticsBaseline::getBaseline('views_harian');
        $baselineViewsTotal = StatisticsBaseline::getBaseline('views_total');

        // Key principle: real_count (from DB) + baseline (manually set starting point) = display value
        // This ensures:
        // - If baseline is set to 10,000, and real_count is 5, display = 10,005
        // - As real_count increments to 6, display becomes 10,006
        // - The baseline acts as a permanent offset, not a replacement

        return [
            'visitHariIni' => max(0, ($raw['visitHariIni'] ?? 0) + $baseline),
            'visitKemarin' => max(0, ($raw['visitKemarin'] ?? 0) + $baselineKemarin),
            'visitMingguIni' => max(0, ($raw['visitMingguIni'] ?? 0) + $baselineMingguan),
            'visitBulanIni' => max(0, ($raw['visitBulanIni'] ?? 0) + $baselineBulanan),
            'visitTahunIni' => max(0, ($raw['visitTahunIni'] ?? 0) + $baselineTahunan),
            'visitTotal' => max(0, ($raw['visitTotal'] ?? 0) + $baselineTotal),
            'viewsHariIni' => max(0, ($raw['visitHariIni'] ?? 0) + $baselineViewsHarian),
            'viewsTotal' => max(0, ($raw['visitTotal'] ?? 0) + $baselineViewsTotal),
        ];
    }

    /**
     * Set visitor count to a specific value (manipulates the baseline, not the real count)
     * This stores the difference as a baseline so future increments work correctly
     * 
     * Example:
     *   - Current displayed value: 500 (real=5 + baseline=495)
     *   - Call setVisitorCount('total', 10000)
     *   - New baseline = 9995 (so 5 + 9995 = 10000)
     *   - Next visitor: increment real count to 6, display = 6 + 9995 = 10001
     */
    public function setVisitorCount(string $period, int $targetValue, ?string $notes = null, ?string $adjustedBy = null): void
    {
        // Get current real count
        $real = $this->getRawVerificationStats();
        $realCount = $real[$period] ?? 0;

        // Calculate new baseline: target_value - real_count
        // This is the manipulation: we're setting where the display should be
        $newBaseline = max(0, $targetValue - $realCount);

        // Map period to baseline key
        $baselineKey = $this->getBaselineKey($period);

        // Store the baseline
        StatisticsBaseline::setBaseline($baselineKey, $newBaseline, $notes, $adjustedBy);
    }

    /**
     * Increment displayed visitor count by incrementing the baseline
     * This is used when you want to manually add visitors to the displayed count
     */
    public function incrementVisitorCount(string $period, int $amount = 1): void
    {
        $baselineKey = $this->getBaselineKey($period);
        StatisticsBaseline::incrementBaseline($baselineKey, $amount);
    }

    /**
     * Reset baseline for a period (so displayed value = real count)
     */
    public function resetBaseline(string $period): void
    {
        $baselineKey = $this->getBaselineKey($period);
        StatisticsBaseline::setBaseline($baselineKey, 0, 'Reset by system', 'system');
    }

    /**
     * Get baseline key for a period
     */
    private function getBaselineKey(string $period): string
    {
        $mapping = [
            'visitHariIni' => 'visitor_harian',
            'visitKemarin' => 'visitor_kemarin',
            'visitMingguIni' => 'visitor_mingguan',
            'visitBulanIni' => 'visitor_bulanan',
            'visitTahunIni' => 'visitor_tahunan',
            'visitTotal' => 'visitor_total',
            'viewsHariIni' => 'views_harian',
            'viewsTotal' => 'views_total',
        ];

        return $mapping[$period] ?? $period;
    }

    /**
     * Get diagnostic information (for debugging)
     * Shows real counts vs displayed counts
     */
    public function getDiagnostics(): array
    {
        $raw = $this->getRawVerificationStats();
        $displayed = $this->getDisplayedStats();
        $baselines = [
            'visitor_harian' => StatisticsBaseline::getBaseline('visitor_harian'),
            'visitor_mingguan' => StatisticsBaseline::getBaseline('visitor_mingguan'),
            'visitor_bulanan' => StatisticsBaseline::getBaseline('visitor_bulanan'),
            'visitor_tahunan' => StatisticsBaseline::getBaseline('visitor_tahunan'),
            'visitor_total' => StatisticsBaseline::getBaseline('visitor_total'),
        ];

        return [
            'raw_counts' => $raw,
            'baselines' => $baselines,
            'displayed_stats' => $displayed,
            'verification' => [
                'daily_calculation' => ($raw['visitHariIni'] ?? 0) + ($baselines['visitor_harian'] ?? 0),
                'total_calculation' => ($raw['visitTotal'] ?? 0) + ($baselines['visitor_total'] ?? 0),
            ],
        ];
    }
}
