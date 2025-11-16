<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Laravel default inspiring quote
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// MONTHLY BILL GENERATOR – Runs every 5th of the month at 00:01
Schedule::command('bills:generate')->monthlyOn(5, '00:01');

// Optional: Run every minute during development so you can test instantly
// Schedule::command('bills:generate')->everyMinute();