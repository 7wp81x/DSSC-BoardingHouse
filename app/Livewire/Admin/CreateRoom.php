<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;

class CreateRoom extends Component
{
    use WithFileUploads;

    public $room_code = '';
    public $type = '';
    public $price = '';
    public $description = '';
    public $status = 'available';
    public $amenities = [];
    public $amenityInput = '';
    public $images = [];

    public function rules()
    {
        return [
            'room_code' => ['required', 'string', 'max:50', 'unique:rooms,room_code'],
            'type' => 'required|in:single,twin,quad,premium',
            'price' => 'required|numeric|min:1',
            'status' => 'required|in:available,occupied,full',
            'description' => 'nullable|string|max:500',
            'images.*' => 'image|max:5120',
            'amenities.*' => 'string|max:100',
        ];
    }

    public function addAmenity()
    {
        if ($this->amenityInput && !in_array($this->amenityInput, $this->amenities)) {
            $this->amenities[] = trim($this->amenityInput);
            $this->amenityInput = '';
        }
    }

    public function removeAmenity($index)
    {
        unset($this->amenities[$index]);
        $this->amenities = array_values($this->amenities);
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function save()
    {
        $this->validate();

        $roomData = [
            'room_code' => $this->room_code,
            'type' => $this->type,
            'price' => $this->price,
            'description' => $this->description,
            'status' => $this->status,
            'amenities' => $this->amenities,
        ];

        $room = Room::create($roomData);

        if ($this->images) {
            foreach ($this->images as $index => $image) {
                $path = $image->store('room-images', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index + 1,
                ]);
            }
        }

        session()->flash('success', 'Room created successfully!');
        return redirect()->route('admin.rooms');
    }

    public function cancel()
    {
        return redirect()->route('admin.rooms');
    }

    public function render()
    {
        return view('livewire.admin.create-room')->layout('layouts.app');
    }
}