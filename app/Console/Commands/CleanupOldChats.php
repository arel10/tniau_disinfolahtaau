<?php

namespace App\Console\Commands;

use App\Models\ChatSession;
use Illuminate\Console\Command;

class CleanupOldChats extends Command
{
    protected $signature = 'chat:cleanup-old {--days=30 : Delete sessions ended older than N days}';

    protected $description = 'Delete old chat sessions and related messages safely';

    public function handle(): int
    {
        $days = max((int) $this->option('days'), 1);
        $cutoff = now()->subDays($days);

        $query = ChatSession::query()
            ->whereNotNull('ended_at')
            ->where('ended_at', '<', $cutoff);

        $count = $query->count();
        if ($count === 0) {
            $this->info('No old chat sessions to delete.');
            return self::SUCCESS;
        }

        $this->info("Deleting {$count} chat sessions older than {$days} days...");

        $deleted = 0;
        $query->orderBy('id')->chunkById(500, function ($sessions) use (&$deleted) {
            foreach ($sessions as $session) {
                $session->delete(); // chat_messages removed via FK cascadeOnDelete
                $deleted++;
            }
        });

        $this->info("Deleted {$deleted} chat sessions.");
        return self::SUCCESS;
    }
}
