<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;   // ← ADD THIS LINE

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register our role middleware with the alias 'role'
        $middleware->alias([
            'role' => CheckRole::class,
        ]);
    })->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // Generate monthly bills on the 1st of every month at 00:00
        $schedule->command('bills:generate')->monthlyOn(1, '00:00');
        
        // Update overdue bills daily at 06:00
        $schedule->command('bills:update-overdue')->dailyAt('06:00');
    })->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();