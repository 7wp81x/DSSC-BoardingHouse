<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;

class EditRoom extends Component
{
    use WithFileUploads;

    public $room;
    public $room_code = '';
    public $type = '';
    public $price = '';
    public $description = '';
    public $status = 'available';
    public $amenities = [];
    public $amenityInput = '';
    public $images = [];
    public $existingImages = [];

    public function rules()
    {
        return [
            'room_code' => ['required', 'string', 'max:50', 'unique:rooms,room_code,' . $this->room->id],
            'type' => 'required|in:single,twin,quad,premium',
            'price' => 'required|numeric|min:1',
            'status' => 'required|in:available,occupied,full',
            'description' => 'nullable|string|max:500',
            'images.*' => 'image|max:5120',
            'amenities.*' => 'string|max:100',
        ];
    }

    public function mount($room)
    {
        $this->room = Room::with('images')->findOrFail($room);
        $this->room_code = $this->room->room_code;
        $this->type = $this->room->type;
        $this->price = $this->room->price;
        $this->description = $this->room->description;
        $this->status = $this->room->status;
        $this->amenities = $this->room->amenities ?? [];
        $this->loadExistingImages();
    }

    protected function loadExistingImages()
    {
        $this->existingImages = $this->room->images->map(fn($image) => [
            'id' => $image->id,
            'image_path' => $image->image_path,
        ])->toArray();
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

    public function deleteExistingImage($imageId)
    {
        $image = RoomImage::findOrFail($imageId);
        
        // Check if this is the only image
        if ($this->room->images()->count() <= 1) {
            session()->flash('error', 'Cannot delete the only image. Room must have at least one image.');
            return;
        }

        // Delete from storage
        Storage::disk('public')->delete($image->image_path);
        
        // Delete from database
        $image->delete();

        // Reload existing images
        $this->loadExistingImages();

        session()->flash('success', 'Image deleted successfully.');
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

        $this->room->update($roomData);

        if ($this->images) {
            $existingCount = $this->room->images()->count();
            foreach ($this->images as $index => $image) {
                $path = $image->store('room-images', 'public');
                RoomImage::create([
                    'room_id' => $this->room->id,
                    'image_path' => $path,
                    'is_primary' => ($existingCount === 0 && $index === 0),
                    'order' => $existingCount + $index + 1,
                ]);
            }
        }

        // Ensure primary image
        if ($this->room->images()->exists() && !$this->room->images()->where('is_primary', true)->exists()) {
            $this->room->images()->orderBy('order')->first()->update(['is_primary' => true]);
        }

        session()->flash('success', 'Room updated successfully!');
        return redirect()->route('admin.rooms');
    }

    public function cancel()
    {
        return redirect()->route('admin.rooms');
    }

    public function render()
    {
        return view('livewire.admin.edit-room')->layout('layouts.app');
    }
}