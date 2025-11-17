<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Announcement;
use App\Models\Booking;
use App\Models\Bill;

class Dashboard extends Component
{
    public $activeBookings;
    public $pendingBookings;
    public $nextBill;
    public $announcements;
    
    // Dashboard stats
    public $activeRoomsCount = 0;
    public $pendingRoomsCount = 0;
    public $pendingBillsCount = 0;
    public $totalMonthlyRent = 0;

    public function mount()
    {
        $user = auth()->user();
        
        // Load ACTIVE bookings only for room display
        $this->activeBookings = Booking::with(['room.currentStudents.user', 'room.images'])
            ->where('user_id', $user->id)
            ->where('status', 'active') // ← ONLY ACTIVE BOOKINGS
            ->get();
            
        // Load PENDING bookings separately
        $this->pendingBookings = Booking::with('room')
            ->where('user_id', $user->id)
            ->where('status', 'pending') // ← ONLY PENDING BOOKINGS
            ->get();
            
        $this->loadDashboardStats();
        $this->loadNextBill();
        $this->announcements = Announcement::latest()->take(5)->get();
    }

    private function loadDashboardStats()
    {
        $this->activeRoomsCount = $this->activeBookings->count();
        $this->pendingRoomsCount = $this->pendingBookings->count();
        
        // Calculate total monthly rent from ACTIVE rooms only
        $this->totalMonthlyRent = $this->activeBookings->sum(function($booking) {
            return $booking->room ? $booking->room->price : 0;
        });
        
        // Load pending bills count from ACTIVE bookings
        $bookingIds = $this->activeBookings->pluck('id');
        $this->pendingBillsCount = Bill::whereIn('booking_id', $bookingIds)
            ->whereIn('status', ['pending', 'overdue', 'pending_payment'])
            ->count();
    }

    private function loadNextBill()
    {
        // Only look for bills from ACTIVE bookings
        $activeBookingIds = $this->activeBookings->pluck('id');
        
        if ($activeBookingIds->isNotEmpty()) {
            // Look for pending OR overdue bills, ordered by due date
            $this->nextBill = Bill::whereIn('booking_id', $activeBookingIds)
                ->whereIn('status', ['pending', 'overdue'])
                ->orderBy('due_date', 'asc')
                ->first();

            // If no pending/overdue bills, check if any bills exist at all
            if (!$this->nextBill) {
                $this->nextBill = Bill::whereIn('booking_id', $activeBookingIds)
                    ->orderBy('due_date', 'desc')
                    ->first();
            }
        }
    }

    public function render()
    {
        return view('livewire.student.dashboard', [
            'activeBookings' => $this->activeBookings,
            'pendingBookings' => $this->pendingBookings,
            'nextBill' => $this->nextBill,
            'announcements' => $this->announcements,
            'activeRoomsCount' => $this->activeRoomsCount,
            'pendingRoomsCount' => $this->pendingRoomsCount,
            'pendingBillsCount' => $this->pendingBillsCount,
            'totalMonthlyRent' => $this->totalMonthlyRent,
        ])->layout('layouts.app');
    }
}