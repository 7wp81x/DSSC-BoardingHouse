<?php

namespace App\Livewire\Student;

use App\Models\Room as RoomModel;
use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Room extends Component
{
    public $myRoom = null;
    public $availableRooms;
    public $selectedType = 'all';

    public function mount()
    {
        // Current active booking + room (or pending if approved later)
        $this->myRoom = Auth::user()
            ->bookings()
            ->whereIn('status', ['active', 'pending'])  // Include pending for visibility
            ->with('room')
            ->latest('check_in_date')
            ->first()?->room;

        $this->loadAvailableRooms();
    }

    public function updatedSelectedType()
    {
        $this->loadAvailableRooms();
    }


    public function requestBoarding($roomId)
    {
        $user = Auth::user();

        // Prevent duplicate request (pending, active, or approved boarder)
        if ($user->studentBoarder && in_array($user->studentBoarder->approval_status, ['pending', 'approved'])) {
            $this->dispatch('notify', 'You already have a boarding request or active boarding. Contact admin if needed.');
            return;
        }

        // Create pending booking
        $booking = $user->bookings()->create([
            'room_id' => $roomId,
            'check_in_date' => now(),  // Or add input for future date
            'monthly_due_date' => 5,  // ← Integer for 5th of month (1-31)
            'status' => 'pending',  // Key change: Pending for admin approval
        ]);

        // Auto-create pending StudentBoarder
        $booking->studentBoarder()->create([
            'user_id' => $user->id,
            'approval_status' => 'pending',
            'room_assignment_notes' => 'Requested via student panel on ' . now()->format('Y-m-d H:i'),
        ]);

        // Hold the room (prevent other bookings)
        RoomModel::where('id', $roomId)->update(['status' => 'maintenance']);

        $this->dispatch('notify', 'Boarding request submitted! Awaiting admin approval. You\'ll be notified soon.');
        $this->mount();  // Refresh myRoom/available (removes held room)
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function render()
    {
        return view('livewire.student.room')->layout('layouts.app');
    }


    public function loadAvailableRooms()
    {
        $query = RoomModel::query();

        if ($this->selectedType !== 'all') {
            $query->where('type', $this->selectedType);
        }

        $rooms = $query->get();

        // FIX: Convert grouped collections into arrays
        $this->availableRooms = $rooms
            ->groupBy('status')
            ->map(fn($group) => $group->values()->toArray()) // ← Important
            ->toArray(); // ← Also required
    }


}