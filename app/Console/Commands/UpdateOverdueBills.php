<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;

class UpdateOverdueBills extends Command
{
    protected $signature = 'bills:update-overdue';
    protected $description = 'Update overdue bills status';

    public function handle()
    {
        $overdueCount = Bill::where('status', Bill::STATUS_PENDING)
            ->where('due_date', '<', now())
            ->update(['status' => Bill::STATUS_OVERDUE]);

        $this->info("Updated {$overdueCount} bills to overdue status.");
    }
}