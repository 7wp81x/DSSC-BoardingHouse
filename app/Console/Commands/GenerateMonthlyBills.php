<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Bill;
use Carbon\Carbon;

class GenerateMonthlyBills extends Command
{
    protected $signature = 'bills:generate';
    protected $description = 'Generate monthly bills for active bookings';

    public function handle()
    {
        $bookings = Booking::where('status', 'active')
            ->with('room') // Eager load room to avoid N+1 queries
            ->get();
            
        $billsCreated = 0;
        $skippedBookings = 0;

        foreach ($bookings as $booking) {
            // Skip if booking doesn't have a room assigned
            if (!$booking->room) {
                $this->warn("Skipping booking {$booking->id} - no room assigned");
                $skippedBookings++;
                continue;
            }

            // Skip if room doesn't have a price
            if (!$booking->room->price || $booking->room->price <= 0) {
                $this->warn("Skipping booking {$booking->id} - room has invalid price: {$booking->room->price}");
                $skippedBookings++;
                continue;
            }

            $existing = Bill::where('booking_id', $booking->id)
                ->where('type', 'rent')
                ->whereYear('due_date', now()->year)
                ->whereMonth('due_date', now()->month)
                ->exists();

            if (!$existing) {
                Bill::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'amount' => $booking->room->price,
                    'due_date' => $this->calculateDueDate($booking),
                    'type' => 'rent',
                    'description' => 'Monthly rent for ' . $booking->room->room_code . ' - ' . now()->format('F Y'),
                    'status' => 'pending'
                ]);
                $billsCreated++;
                $this->info("Created bill for {$booking->user->name} - Room: {$booking->room->room_code}");
            }
        }

        $this->info("Monthly bills generated! Created {$billsCreated} new bills.");
        if ($skippedBookings > 0) {
            $this->warn("Skipped {$skippedBookings} bookings due to missing room or invalid price.");
        }
    }

    private function calculateDueDate(Booking $booking)
    {
        if ($booking->monthly_due_date) {
            $dueDate = now()->startOfMonth()->addDays($booking->monthly_due_date - 1);
            
            if ($dueDate->isPast()) {
                $dueDate = $dueDate->addMonth();
            }
            
            return $dueDate;
        }

        // Default to 5th of next month
        return now()->addMonth()->startOfMonth()->addDays(4);
    }
}