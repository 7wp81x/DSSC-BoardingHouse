<?php

namespace App\Livewire\Student;

use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Payments extends Component
{
    public $booking;
    public $pendingBills;

    public function mount()
    {
        // Load the student's active booking with bills
        $this->booking = Auth::user()
            ->bookings()
            ->with(['room', 'bills' => function ($q) {
                $q->whereIn('status', ['pending', 'overdue'])->orderBy('due_date');
            }])
            ->where('status', 'active')
            ->first();

        $this->pendingBills = $this->booking?->bills ?? collect();
    }

    public function render()
    {
        return view('livewire.student.payments')->layout('layouts.app');
    }
}