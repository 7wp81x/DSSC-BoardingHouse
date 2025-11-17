<?php

namespace App\Livewire\Student;

use App\Models\Room as RoomModel;
use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Room extends Component
{
    public $myRoom = null;
    public $availableRooms = [];
    public $selectedType = 'all';
    public $viewerActive = false;
    public $selectedRoom = null;
    public $currentImageIndex = 0;
    public $showRoomInfo = false;

    public function mount()
    {
        $this->loadMyRoom();
        $this->loadAvailableRooms();
    }

    public function loadMyRoom()
    {
        $this->myRoom = Auth::user()
            ->bookings()
            ->whereIn('status', ['active', 'pending'])
            ->with('room')
            ->latest('check_in_date')
            ->first()?->room;
    }

    public function updatedSelectedType()
    {
        $this->loadAvailableRooms();
    }

    public function loadAvailableRooms()
    {
        $query = RoomModel::with(['images', 'currentStudents.user']);

        if ($this->selectedType !== 'all') {
            $query->where('type', $this->selectedType);
        }

        $rooms = $query->get();

        $this->availableRooms = $rooms
            ->groupBy('status')
            ->map(fn($group) => $group->map(function($room) {
                return [
                    'id' => $room->id,
                    'room_code' => $room->room_code,
                    'type' => $room->type,
                    'price' => $room->price,
                    'description' => $room->description,
                    'status' => $room->status,
                    'amenities' => $room->amenities ?? [],
                    'images' => $room->images->map(function($image) {
                        return [
                            'image_path' => $image->image_path,
                            'id' => $image->id
                        ];
                    })->toArray(),
                    'current_students' => $room->currentStudents->map(function($student) {
                        return [
                            'name' => $student->user->name,
                            'email' => $student->user->email,
                        ];
                    })->toArray()
                ];
            })->toArray())
            ->toArray();
    }

    public function requestBoarding($roomId)
    {
        if ($this->myRoom) {
            $this->dispatch('notify', 'You already have a room booking. Please contact admin for changes.');
            return;
        }

        $user = Auth::user();

        if ($user->studentBoarder && in_array($user->studentBoarder->approval_status, ['pending', 'approved'])) {
            $this->dispatch('notify', 'You already have a boarding request or active boarding. Contact admin if needed.');
            return;
        }

        $booking = $user->bookings()->create([
            'room_id' => $roomId,
            'check_in_date' => now(),
            'monthly_due_date' => 5,
            'status' => 'pending',
        ]);

        $booking->studentBoarder()->create([
            'user_id' => $user->id,
            'approval_status' => 'pending',
            'room_assignment_notes' => 'Requested via student panel on ' . now()->format('Y-m-d H:i'),
        ]);

        RoomModel::where('id', $roomId)->update(['status' => 'maintenance']);

        $this->dispatch('notify', 'Boarding request submitted! Awaiting admin approval. You\'ll be notified soon.');
        $this->mount();
    }

    public function openViewer($roomId)
    {
        $room = RoomModel::with('images')->find($roomId);
        if ($room) {
            $this->selectedRoom = [
                'id' => $room->id,
                'room_code' => $room->room_code,
                'type' => $room->type,
                'price' => $room->price,
                'images' => $room->images->map(function($image) {
                    return [
                        'image_path' => $image->image_path,
                        'id' => $image->id
                    ];
                })->toArray()
            ];
            $this->currentImageIndex = 0;
            $this->viewerActive = true;
        }
    }

    public function closeViewer()
    {
        $this->viewerActive = false;
        $this->selectedRoom = null;
        $this->currentImageIndex = 0;
    }

    public function nextImage()
    {
        if ($this->selectedRoom && count($this->selectedRoom['images']) > 0) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->selectedRoom['images']);
        }
    }

    public function prevImage()
    {
        if ($this->selectedRoom && count($this->selectedRoom['images']) > 0) {
            $this->currentImageIndex = ($this->currentImageIndex - 1 + count($this->selectedRoom['images'])) % count($this->selectedRoom['images']);
        }
    }

    public function setImage($index)
    {
        $this->currentImageIndex = $index;
    }

    public function viewRoomDetails($roomId)
    {
        return redirect()->route('student.room.view', $roomId);
    }

    public function closeRoomInfo()
    {
        $this->showRoomInfo = false;
        $this->selectedRoom = null;
    }

    public function render()
    {
        return view('livewire.student.room')->layout('layouts.app');
    }
}