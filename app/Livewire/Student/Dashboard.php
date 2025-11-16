<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Announcement;
use App\Models\Booking;

class Dashboard extends Component
{
    public $booking;
    public $nextBill;
    public $announcements;

    public function mount()
    {
        $user = auth()->user();

        $this->booking = Booking::with('room')->where('user_id', $user->id)->first();
        $this->nextBill = $this->booking ? $this->booking->bills()->where('status', 'pending')->orderBy('due_date')->first() : null;
        $this->announcements = Announcement::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.student.dashboard', [
            'booking' => $this->booking,
            'nextBill' => $this->nextBill,
            'announcements' => $this->announcements,
        ])->layout('layouts.app'); // keep your app layout
    }
}
