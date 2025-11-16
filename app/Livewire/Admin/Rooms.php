<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class Rooms extends Component
{
    public $search = '';
    public $selectedRoom = null;
    public $currentImageIndex = 0;
    public $viewerActive = false;
    public $showDeleteModal = false;
    public $roomToDelete = null;
    public $rooms;

    public function mount()
    {
        $this->loadRooms();
    }

    public function loadRooms()
    {
        $query = Room::with(['images', 'currentStudents.user'])->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('room_code', 'like', '%' . $this->search . '%')
                  ->orWhere('id', $this->search);
            });
        }

        $this->rooms = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadRooms();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadRooms();
    }

    public function confirmDelete($id)
    {
        $this->roomToDelete = Room::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->roomToDelete = null;
    }

    public function delete($id)
    {
        $room = Room::findOrFail($id);
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        $room->delete();
        $this->closeDeleteModal();
        $this->loadRooms();
        
        session()->flash('success', 'Room deleted successfully!');
    }

    public function openViewer($roomId)
    {
        $this->selectedRoom = Room::with('images')->findOrFail($roomId);
        $this->currentImageIndex = 0;
        $this->viewerActive = true;
    }

    public function closeViewer()
    {
        $this->viewerActive = false;
        $this->selectedRoom = null;
        $this->currentImageIndex = 0;
    }

    public function nextImage()
    {
        if ($this->selectedRoom && $this->selectedRoom->images->count() > 0) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % $this->selectedRoom->images->count();
        }
    }

    public function prevImage()
    {
        if ($this->selectedRoom && $this->selectedRoom->images->count() > 0) {
            $this->currentImageIndex = ($this->currentImageIndex - 1 + $this->selectedRoom->images->count()) % $this->selectedRoom->images->count();
        }
    }

    public function setImage($index)
    {
        $this->currentImageIndex = $index;
    }

    public function render()
    {
        return view('livewire.admin.rooms')->layout('layouts.app');
    }
}