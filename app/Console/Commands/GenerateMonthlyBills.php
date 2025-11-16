<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Bill;

class GenerateMonthlyBills extends Command
{
    protected $signature = 'bills:generate';
    protected $description = 'Generate monthly bills for active bookings';

    public function handle()
    {
        $bookings = Booking::where('status', 'active')->get();
        foreach ($bookings as $booking) {
            $existing = Bill::where('booking_id', $booking->id)
                ->whereYear('due_date', now()->year)
                ->whereMonth('due_date', now()->month)
                ->exists();

            if (!$existing) {
                Bill::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->room->price,
                    'due_date' => now()->startOfMonth()->addDays(4),
                    'status' => 'pending'
                ]);
            }
        }
        $this->info('Monthly bills generated!');
    }
}
