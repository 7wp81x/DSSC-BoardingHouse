<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Bill;
use Carbon\Carbon;

class BillGenerator
{
    public function generateInitialBill(Booking $booking)
    {
        // Check if room exists and has price
        if (!$booking->room || !$booking->room->price) {
            return null;
        }

        // Check if bill already exists for this month
        $currentMonth = now()->startOfMonth();
        $existingBill = Bill::where('booking_id', $booking->id)
            ->where('type', 'rent')
            ->whereYear('due_date', $currentMonth->year)
            ->whereMonth('due_date', $currentMonth->month)
            ->first();

        if ($existingBill) {
            return $existingBill;
        }

        // Calculate due date
        $dueDate = $this->calculateDueDate($booking);

        // Create new bill
        return Bill::create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'amount' => $booking->room->price,
            'due_date' => $dueDate,
            'type' => 'rent',
            'description' => 'Monthly rent for ' . $booking->room->room_code . ' - ' . now()->format('F Y'),
            'status' => 'pending'
        ]);
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

    public function generateMonthlyBills()
    {
        $activeBookings = Booking::with(['room', 'user'])
            ->where('status', 'active')
            ->get();

        $billsCreated = 0;

        foreach ($activeBookings as $booking) {
            // Skip if no room or invalid price
            if (!$booking->room || !$booking->room->price || $booking->room->price <= 0) {
                continue;
            }

            $bill = $this->generateInitialBill($booking);
            if ($bill) {
                $billsCreated++;
            }
        }

        return $billsCreated;
    }

    public function updateOverdueBills()
    {
        return Bill::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }
}