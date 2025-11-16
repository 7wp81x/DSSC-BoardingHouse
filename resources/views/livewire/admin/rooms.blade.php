<div>
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                @if($currentView === 'grid')
                    Manage Rooms
                @else
                    {{ $editingId ? 'Edit Room' : 'Add New Room' }}
                @endif
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                @if($currentView === 'grid')
                    View and manage all room listings
                @else
                    {{ $editingId ? 'Update room information' : 'Create a new room listing' }}
                @endif
            </p>
        </div>
        
        <!-- View Toggle Buttons -->
        <div class="flex gap-3">
            @if($currentView === 'grid')
                <button 
                    type="button" 
                    wire:click="showForm" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors"
                >
                    <i class="fas fa-plus"></i>
                    Add New Room
                </button>
            @else
                <button 
                    type="button" 
                    wire:click="showGrid" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors"
                >
                    <i class="fas fa-list"></i>
                    View All Rooms
                </button>
            @endif
        </div>
    </div>

    <!-- Room Form View -->
    @if($currentView === 'form')
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 mb-8 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas {{ $editingId ? 'fa-edit' : 'fa-plus' }} mr-2"></i>
                {{ $editingId ? 'Edit Room' : 'Add New Room' }}
            </h3>
        </div>
        
        <form wire:submit.prevent="save" class="p-6 space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Room Code <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="room_code" 
                        placeholder="e.g., 101, 201-A" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        required
                    >
                    @error('room_code') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Room Type <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="type" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        required
                    >
                        <option value="">Select Type</option>
                        <option value="single">Single</option>
                        <option value="twin">Twin Sharing</option>
                        <option value="quad">Quad</option>
                        <option value="premium">Premium</option>
                    </select>
                    @error('type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price per Month (₱) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        wire:model="price" 
                        placeholder="0.00" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        required
                    >
                    @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <textarea 
                    wire:model="description" 
                    rows="3" 
                    placeholder="Describe the room features, view, furniture, etc."
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                ></textarea>
                @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Images Section -->
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Room Images
                </label>
                
                <!-- File Upload -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center transition-colors hover:border-blue-400">
                    <input 
                        type="file" 
                        wire:model="images" 
                        multiple 
                        accept="image/*" 
                        class="hidden" 
                        id="imageUpload"
                    >
                    <label for="imageUpload" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 dark:text-gray-400 mb-1">
                            Click to upload images or drag and drop
                        </p>
                        <p class="text-sm text-gray-500">
                            PNG, JPG, JPEG up to 5MB each
                        </p>
                    </label>
                </div>

                <!-- New Image Previews -->
                @if($images)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($images as $index => $image)
                    <div class="relative group">
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg shadow-sm">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all flex items-center justify-center">
                            <button 
                                type="button" 
                                wire:click="removeImage({{ $index }})" 
                                class="text-white opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all bg-red-500 hover:bg-red-600 w-8 h-8 rounded-full flex items-center justify-center"
                            >
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Existing Images (during edit) -->
                @if($editingId && count($existingImages) > 0)
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Existing Images
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($existingImages as $img)
                        <div class="relative group">
                            <img src="{{ Storage::url($img['image_path']) }}" class="w-full h-24 object-cover rounded-lg shadow-sm">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all flex items-center justify-center">
                                <button 
                                    type="button" 
                                    wire:click="removeExistingImage({{ $img['id'] }})" 
                                    class="text-white opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all bg-red-500 hover:bg-red-600 w-8 h-8 rounded-full flex items-center justify-center"
                                >
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Amenities -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Amenities
                </label>
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50 min-h-24">
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($amenities as $index => $amenity)
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-2 rounded-full text-sm font-medium flex items-center gap-2 group">
                            {{ $amenity }}
                            <button 
                                type="button" 
                                wire:click="removeAmenity({{ $index }})" 
                                class="text-white dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors group-hover:scale-110 transform"
                            >
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </span>
                        @endforeach
                    </div>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            wire:model="amenityInput" 
                            wire:keydown.enter.prevent="addAmenity"
                            placeholder="Type amenity and press Enter" 
                            class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        >
                        <button 
                            type="button" 
                            wire:click="addAmenity" 
                            class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                        >
                            Add
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                <button 
                    type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors"
                    wire:loading.attr="disabled"
                    wire:target="save"
                >
                    <i class="fas fa-check"></i>
                    <span wire:loading.remove wire:target="save">
                        {{ $editingId ? 'Update Room' : 'Create Room' }}
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="fas fa-spinner fa-spin"></i>
                        Processing...
                    </span>
                </button>
                <button 
                    type="button" 
                    wire:click="cancel" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors"
                    wire:loading.attr="disabled"
                    wire:target="save"
                >
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Rooms Grid View -->
    @if($currentView === 'grid' && !$viewerActive)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($rooms as $room)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden group hover:shadow-xl transition-all duration-300">
            <!-- Image Section -->
            <div class="relative overflow-hidden">
                @if($room->images->isNotEmpty())
                    <img 
                        src="{{ Storage::url($room->images->first()->image_path) }}" 
                        alt="Room {{ $room->room_code }}"
                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    @if($room->images->count() > 1)
                    <div class="absolute top-3 right-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs backdrop-blur-sm">
                        <i class="fas fa-images mr-1"></i>{{ $room->images->count() }}
                    </div>
                    @endif
                    <div class="absolute bottom-3 left-3">
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                            {{ ucfirst($room->type) }}
                        </span>
                    </div>
                    <!-- View Gallery Button -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                        <button 
                            wire:click="openViewer({{ $room->id }})"
                            class="text-white opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg font-medium flex items-center gap-2"
                        >
                            <i class="fas fa-expand"></i>
                            View Gallery
                        </button>
                    </div>
                @else
                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Room {{ $room->room_code }}</h3>
                    <span class="text-2xl font-bold text-white">₱{{ number_format($room->price) }}</span>
                </div>

                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                    {{ $room->description ?: 'No description provided.' }}
                </p>

                <!-- Status -->
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $room->status === 'available' ? 'bg-white-100 text-white-800 dark:bg-white-900 dark:text-white-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ ucfirst($room->status) }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per month</span>
                </div>

                <!-- Amenities Preview -->
                @if($room->amenities && count($room->amenities) > 0)
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach(array_slice($room->amenities, 0, 3) as $amenity)
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs">
                        {{ $amenity }}
                    </span>
                    @endforeach
                    @if(count($room->amenities) > 3)
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-1 rounded text-xs">
                        +{{ count($room->amenities) - 3 }} more
                    </span>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <button 
                        wire:click="edit({{ $room->id }})"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                    <button 
                        wire:click="delete({{ $room->id }})" 
                        onclick="return confirm('Are you sure you want to delete this room? This action cannot be undone.')"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-trash"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Empty State -->
        @if($rooms->isEmpty())
        <div class="col-span-full text-center py-12">
            <i class="fas fa-home text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">No rooms found</h3>
            <p class="text-gray-500 dark:text-gray-500 mb-6">Get started by adding your first room</p>
            <button 
                type="button" 
                wire:click="showForm" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 mx-auto transition-colors"
            >
                <i class="fas fa-plus"></i>
                Add New Room
            </button>
        </div>
        @endif
    </div>
    @endif

    <!-- Enhanced Image Viewer -->
    @if($viewerActive && $selectedRoom)
    <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
        <!-- Close Button -->
        <button 
            wire:click="closeViewer" 
            class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors z-10 bg-black bg-opacity-50 w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-sm"
        >
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Main Image Container -->
        <div class="relative max-w-6xl w-full max-h-[80vh] flex items-center justify-center">
            <!-- Navigation Arrows -->
            <button 
                wire:click="prevImage" 
                class="absolute left-4 lg:left-6 text-white hover:text-gray-300 transition-colors z-10 bg-black bg-opacity-50 w-12 h-12 rounded-full flex items-center justify-center backdrop-blur-sm"
            >
                <i class="fas fa-chevron-left text-xl"></i>
            </button>

            <button 
                wire:click="nextImage" 
                class="absolute right-4 lg:right-6 text-white hover:text-gray-300 transition-colors z-10 bg-black bg-opacity-50 w-12 h-12 rounded-full flex items-center justify-center backdrop-blur-sm"
            >
                <i class="fas fa-chevron-right text-xl"></i>
            </button>

            <!-- Main Image -->
            <div class="w-full h-full flex items-center justify-center">
                <img 
                    src="{{ Storage::url($selectedRoom->images[$currentImageIndex]->image_path) }}" 
                    alt="Room {{ $selectedRoom->room_code }} - Image {{ $currentImageIndex + 1 }}"
                    class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-2xl"
                >
            </div>
        </div>

        <!-- Room Info Panel -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 backdrop-blur-sm rounded-2xl p-6 text-white max-w-2xl w-full">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-2">Room {{ $selectedRoom->room_code }}</h3>
                <p class="text-gray-300 mb-3">{{ ucfirst($selectedRoom->type) }} Room • ₱{{ number_format($selectedRoom->price) }}/month</p>
                
                <!-- Image Counter -->
                <div class="flex items-center justify-center gap-4 mb-4">
                    <span class="text-sm text-gray-300">
                        {{ $currentImageIndex + 1 }} of {{ $selectedRoom->images->count() }}
                    </span>
                </div>

                <!-- Thumbnail Navigation -->
                @if($selectedRoom->images->count() > 1)
                <div class="flex gap-2 justify-center overflow-x-auto pb-2">
                    @foreach($selectedRoom->images as $index => $image)
                    <button 
                        wire:click="setImage({{ $index }})"
                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all {{ $index === $currentImageIndex ? 'border-blue-500 scale-110' : 'border-transparent opacity-60 hover:opacity-100' }}"
                    >
                        <img 
                            src="{{ Storage::url($image->image_path) }}" 
                            alt="Thumbnail {{ $index + 1 }}"
                            class="w-full h-full object-cover"
                        >
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Keyboard Shortcuts Info -->
        <div class="absolute top-6 left-6 text-white text-sm opacity-60">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1">
                    <kbd class="bg-gray-800 px-2 py-1 rounded text-xs">←</kbd> Previous
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="bg-gray-800 px-2 py-1 rounded text-xs">→</kbd> Next
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="bg-gray-800 px-2 py-1 rounded text-xs">ESC</kbd> Close
                </span>
            </div>
        </div>
    </div>

    <!-- Keyboard Navigation -->
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight' || e.key === 'Escape') {
                @this.call(e.key === 'ArrowLeft' ? 'prevImage' : 
                          e.key === 'ArrowRight' ? 'nextImage' : 'closeViewer');
            }
        });
    </script>
    @endif
</div>

