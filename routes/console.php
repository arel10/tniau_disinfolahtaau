<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Delete old chat sessions/messages older than 30 days.
Schedule::command('chat:cleanup-old --days=30')
    ->dailyAt('02:15')
    ->name('chat-cleanup-old')
    ->withoutOverlapping();
