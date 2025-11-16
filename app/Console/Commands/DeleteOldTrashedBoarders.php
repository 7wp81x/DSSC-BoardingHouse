<?php

namespace App\Console\Commands;

use App\Models\StudentBoarder;
use Illuminate\Console\Command;

class DeleteOldTrashedBoarders extends Command
{
    protected $signature = 'boarders:delete-old-trashed';
    protected $description = 'Permanently delete trashed student boarders older than 30 days';

    public function handle()
    {
        $deleted = StudentBoarder::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->forceDelete();

        $this->info("Deleted {$deleted} old trashed boarders.");
    }
}