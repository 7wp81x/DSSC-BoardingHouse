<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;

class Rooms extends Component
{
    use WithFileUploads;

    public $currentView = 'grid'; // 'grid', 'form'
    public $editingId = null;
    public $room_code = '';
    public $type = '';
    public $price = '';
    public $description = '';
    public $amenities = [];
    public $amenityInput = '';
    public $images = [];
    public $existingImages = [];
    public $rooms;
    public $selectedRoom = null;
    public $currentImageIndex = 0;
    public $viewerActive = false;

    protected $rules = [
        'room_code' => 'required|unique:rooms,room_code',
        'type' => 'required|in:single,twin,quad,premium',
        'price' => 'required|numeric|min:1',
        'description' => 'nullable|string|max:500',
        'images.*' => 'image|max:5120', // 5MB per image
    ];

    public function mount()
    {
        $this->loadRooms();
    }

    public function loadRooms()
    {
        $this->rooms = Room::with('images')->latest()->get();
    }

    public function resetForm()
    {
        $this->reset([
            'editingId', 'room_code', 'type', 'price', 'description', 
            'amenities', 'amenityInput', 'images', 'existingImages'
        ]);
        $this->resetErrorBag();
        // Reset the room_code validation rule to default
        $this->rules['room_code'] = 'required|unique:rooms,room_code';
    }

    public function showGrid()
    {
        $this->currentView = 'grid';
        $this->resetForm();
    }

    public function showForm()
    {
        $this->currentView = 'form';
        $this->resetForm();
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

    public function removeExistingImage($id)
    {
        $image = RoomImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        $this->existingImages = array_filter($this->existingImages, fn($img) => $img['id'] !== $id);
    }

    public function edit($id)
    {
        $room = Room::with('images')->findOrFail($id);
        $this->editingId = $id;
        $this->room_code = $room->room_code;
        $this->type = $room->type;
        $this->price = $room->price;
        $this->description = $room->description;
        $this->amenities = $room->amenities ?? [];
        $this->existingImages = $room->images->map(fn($image) => [
            'id' => $image->id,
            'image_path' => $image->image_path,
        ])->toArray();
        
        $this->currentView = 'form';
        // Update validation rule for editing
        $this->rules['room_code'] = 'required|unique:rooms,room_code,' . $id;
    }

    public function save()
    {
        $this->validate();

        $roomData = [
            'room_code' => $this->room_code,
            'type' => $this->type,
            'price' => $this->price,
            'description' => $this->description,
            'amenities' => $this->amenities,
        ];

        if ($this->editingId) {
            $room = Room::findOrFail($this->editingId);
            $room->update($roomData);
            $message = 'Room updated successfully!';
        } else {
            $room = Room::create($roomData);
            $message = 'Room created successfully!';
        }

        if ($this->images) {
            $existingCount = $room->images()->count();
            foreach ($this->images as $index => $image) {
                $path = $image->store('room-images', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                    'is_primary' => ($existingCount === 0 && $index === 0),
                    'order' => $existingCount + $index,
                ]);
            }
        }

        $room->refresh();
        if ($room->images()->exists() && !$room->images()->where('is_primary', true)->exists()) {
            $room->images()->orderBy('order')->first()->update(['is_primary' => true]);
        }

        $this->showGrid(); // Use showGrid to reset everything properly
        $this->loadRooms();
        
        session()->flash('message', $message);
    }

    public function cancel()
    {
        $this->showGrid(); // Use showGrid to reset everything properly
    }

    public function delete($id)
    {
        $room = Room::findOrFail($id);
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        $room->delete();
        $this->loadRooms();
        
        session()->flash('message', 'Room deleted successfully!');
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