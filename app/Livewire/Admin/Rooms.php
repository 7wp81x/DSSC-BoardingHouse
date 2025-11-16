<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Rooms extends Component
{
    use WithFileUploads;

    public $currentView = 'grid';
    public $editingId = null;
    public $room_code = '';
    public $type = '';
    public $price = '';
    public $description = '';
    public $status = 'available';
    public $amenities = [];
    public $amenityInput = '';
    public $images = [];
    public $existingImages = [];
    public $rooms;
    public $search = '';
    public $selectedRoom = null;
    public $currentImageIndex = 0;
    public $viewerActive = false;
    public $showDeleteModal = false;
    public $roomToDelete = null;

    public function rules()
    {
        return [
            'room_code' => ['required', 'string', 'max:50', 'unique:rooms,room_code,' . $this->editingId],
            'type' => 'required|in:single,twin,quad,premium',
            'price' => 'required|numeric|min:1',
            'status' => 'required|in:available,occupied,full',
            'description' => 'nullable|string|max:500',
            'images.*' => 'image|max:5120',
            'amenities.*' => 'string|max:100',
        ];
    }

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

    public function resetForm()
    {
        $this->reset([
            'editingId', 'room_code', 'type', 'price', 'description', 'status', 
            'amenities', 'amenityInput', 'images', 'existingImages'
        ]);
        $this->resetErrorBag();
    }

    public function showGrid()
    {
        $this->currentView = 'grid';
        $this->resetForm();
        $this->loadRooms();
        
        // Force browser reload to reset everything
        $this->js('window.location.reload()');
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

    // Delete existing image from room
    public function deleteExistingImage($imageId)
    {
        $image = RoomImage::findOrFail($imageId);
        
        // Check if this is the only image
        $room = $image->room;
        if ($room->images()->count() <= 1) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Cannot delete the only image. Room must have at least one image.'
            ]);
            return;
        }

        // Delete from storage
        Storage::disk('public')->delete($image->image_path);
        
        // Delete from database
        $image->delete();

        // Reload existing images
        $this->loadExistingImages();

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Image deleted successfully.'
        ]);
    }

    public function removeExistingImage($id)
    {
        $image = RoomImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        $this->loadExistingImages();
    }

    public function edit($id)
    {
        $room = Room::with('images')->findOrFail($id);
        $this->editingId = $id;
        $this->room_code = $room->room_code;
        $this->type = $room->type;
        $this->price = $room->price;
        $this->description = $room->description;
        $this->status = $room->status;
        $this->amenities = $room->amenities ?? [];
        $this->loadExistingImages();
        
        $this->currentView = 'form';
    }

    protected function loadExistingImages()
    {
        if ($this->editingId) {
            $room = Room::find($this->editingId);
            $this->existingImages = $room->images->map(fn($image) => [
                'id' => $image->id,
                'image_path' => $image->image_path,
            ])->toArray();
        }
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
                    'order' => $existingCount + $index + 1,
                ]);
            }
        }

        // Ensure primary image
        if ($room->images()->exists() && !$room->images()->where('is_primary', true)->exists()) {
            $room->images()->orderBy('order')->first()->update(['is_primary' => true]);
        }

        // Show success message and redirect to grid view
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => $message
        ]);

        // Force reload to prevent state issues
        $this->showGrid();
    }

    public function cancel()
    {
        // Force page reload to reset everything
        $this->showGrid();
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
        
        // Show success message and reload
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Room deleted successfully!'
        ]);

        // Force reload after delete
        $this->showGrid();
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