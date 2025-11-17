<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Room;

class ViewRoom extends Component
{
    public $room;
    public $roomDetails;
    public $currentImageIndex = 0;
    public $viewerActive = false;

    public function mount($room)
    {
        $this->room = Room::with('images')->findOrFail($room);
        $this->loadRoomDetails();
    }

    protected function loadRoomDetails()
    {
        $this->roomDetails = [
            'id' => $this->room->id,
            'room_code' => $this->room->room_code,
            'type' => $this->room->type,
            'price' => $this->room->price,
            'description' => $this->room->description,
            'status' => $this->room->status,
            'amenities' => $this->room->amenities ?? [],
            'images' => $this->room->images->map(function($image) {
                return [
                    'image_path' => $image->image_path,
                    'id' => $image->id
                ];
            })->toArray()
        ];
    }

    public function openViewer()
    {
        $this->currentImageIndex = 0;
        $this->viewerActive = true;
    }

    public function closeViewer()
    {
        $this->viewerActive = false;
        $this->currentImageIndex = 0;
    }

    public function nextImage()
    {
        if (!empty($this->roomDetails['images'])) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->roomDetails['images']);
        }
    }

    public function prevImage()
    {
        if (!empty($this->roomDetails['images'])) {
            $this->currentImageIndex = ($this->currentImageIndex - 1 + count($this->roomDetails['images'])) % count($this->roomDetails['images']);
        }
    }

    public function setImage($index)
    {
        $this->currentImageIndex = $index;
    }

    public function render()
    {
        return view('livewire.student.view-room')->layout('layouts.app');
    }
}