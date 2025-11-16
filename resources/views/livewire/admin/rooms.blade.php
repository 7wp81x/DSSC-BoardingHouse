<div class="@if($viewerActive) viewer-active @endif @if($currentView === 'form') current-view-form @endif">
    <!-- Search Bar - Hidden when in form view -->
    @if(!$viewerActive && $currentView === 'grid')
    <div class="mb-6 search-container">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-20">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search rooms by ID or code..." 
                class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
            >
        </div>
        @if($search)
            <button wire:click="clearSearch" class="ml-2 mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 transition-colors">
                Clear Search
            </button>
        @endif
    </div>
    @endif

    <!-- Improved Header Section -->
    @if(!$viewerActive)
    <div class="header-section">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-white">
                    @if($currentView === 'grid')
                        Manage Rooms
                    @else
                        {{ $editingId ? 'Edit Room' : 'Add New Room' }}
                    @endif
                </h2>
                <p class="text-white opacity-90">
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
                        class="bg-white text-indigo-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-all shadow-lg"
                    >
                        <i class="fas fa-plus"></i>
                        Add New Room
                    </button>
                @else
                    <button 
                        type="button" 
                        wire:click="showGrid" 
                        class="bg-white text-gray-700 hover:bg-gray-100 px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-all shadow-lg"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Back to Rooms
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Room Form View -->
    @if($currentView === 'form')
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 mb-8 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas {{ $editingId ? 'fa-edit' : 'fa-plus' }} mr-2"></i>
                {{ $editingId ? 'Edit Room' : 'Add New Room' }}
            </h3>
        </div>
        
        <form wire:submit.prevent="save" class="p-6 space-y-6" enctype="multipart/form-data">
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
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
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
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
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
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="status" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        required
                    >
                        <option value="">Select Status</option>
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="full">Full</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price per Month (₱) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        wire:model="price" 
                        placeholder="0.00" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        required
                        step="0.01"
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
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"
                ></textarea>
                @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Images Section -->
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Room Images
                </label>
                
                <!-- File Upload -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center transition-colors hover:border-indigo-400">
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
                            PNG, JPG, JPEG up to 5MB each (Multiple selection supported)
                        </p>
                    </label>
                    @error('images') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- New Image Previews -->
                @if($images && count($images) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($images as $index => $image)
                    <div class="relative group">
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg shadow-sm" alt="Preview {{ $index + 1 }}">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all flex items-center justify-center">
                            <button 
                                type="button" 
                                wire:click="removeImage({{ $index }})" 
                                class="text-white opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all bg-red-500 hover:bg-red-600 w-8 h-8 rounded-full flex items-center justify-center"
                                title="Remove image"
                            >
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Existing Images (during edit) with delete functionality -->
                @if($editingId && count($existingImages) > 0)
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Existing Images (Click × to delete)
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 existing-images-grid">
                        @foreach($existingImages as $img)
                        <div class="existing-image-item">
                            <img src="{{ Storage::url($img['image_path']) }}" class="w-full h-full object-cover rounded-lg shadow-sm" alt="Existing image">
                            <button 
                                type="button" 
                                wire:click="deleteExistingImage({{ $img['id'] }})" 
                                class="delete-existing-image-btn"
                                title="Delete this image"
                                onclick="return confirm('Are you sure you want to delete this image?')"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Amenities Section with mobile fixes -->
            <div class="amenities-section">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Amenities
                </label>
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50 min-h-24">
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($amenities as $index => $amenity)
                        <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-2 rounded-full text-sm font-medium flex items-center gap-2 group relative">
                            {{ $amenity }}
                            <button 
                                type="button" 
                                wire:click="removeAmenity({{ $index }})" 
                                class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors group-hover:scale-110 transform absolute -top-1 -right-1 bg-white dark:bg-gray-800 rounded-full w-5 h-5 flex items-center justify-center shadow-sm"
                                title="Remove amenity"
                            >
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </span>
                        @endforeach
                    </div>
                    <div class="flex gap-2 amenities-input-container">
                        <input 
                            type="text" 
                            wire:model="amenityInput" 
                            wire:keydown.enter.prevent="addAmenity"
                            placeholder="Type amenity and press Enter" 
                            class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                        >
                        <button 
                            type="button" 
                            wire:click="addAmenity" 
                            class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors whitespace-nowrap"
                        >
                            Add Amenity
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions with mobile fixes -->
            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-600 form-actions">
                <button 
                    type="submit" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors disabled:opacity-50"
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
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors disabled:opacity-50"
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
            <div class="room-image-container relative overflow-hidden">
                @if($room->images->isNotEmpty())
                    <img 
                        src="{{ Storage::url($room->images->first()->image_path) }}" 
                        alt="Room {{ $room->room_code }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    @if($room->images->count() > 1)
                    <div class="absolute top-3 right-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs backdrop-blur-sm">
                        <i class="fas fa-images mr-1"></i>{{ $room->images->count() }}
                    </div>
                    @endif
                    <div class="absolute bottom-3 left-3">
                        <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                            {{ ucfirst($room->type) }}
                        </span>
                    </div>
                    <!-- View Gallery Button -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                        <button 
                            wire:click="openViewer({{ $room->id }})"
                            class="view-gallery-btn flex items-center gap-2"
                        >
                            <i class="fas fa-expand"></i>
                            View Gallery
                        </button>
                    </div>
                @else
                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Room {{ $room->room_code }}</h3>
                    <span class="text-2xl font-bold text-indigo-600">₱{{ number_format($room->price, 2) }}</span>
                </div>

                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                    {{ $room->description ?: 'No description provided.' }}
                </p>

                <!-- Status -->
                <div class="flex items-center justify-between mb-4">
                    @php $statusClass = $room->status === 'available' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : ($room->status === 'occupied' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'); @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ ucfirst($room->status) }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per month</span>
                </div>

                <!-- Amenities Preview -->
                @if($room->amenities && is_array($room->amenities) && count($room->amenities) > 0)
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

                <!-- Students List -->
                @if($room->currentStudents && $room->currentStudents->count() > 0)
                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Current Boarders ({{ $room->currentStudents->count() }})</h4>
                    <div class="space-y-1 max-h-20 overflow-y-auto">
                        @foreach($room->currentStudents as $student)
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <div class="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-white font-medium">
                                {{ Str::substr($student->user->name, 0, 1) }}
                            </div>
                            <span>{{ $student->user->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <button 
                        wire:click="edit({{ $room->id }})"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                    <button 
                        wire:click="confirmDelete({{ $room->id }})" 
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
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 mx-auto transition-colors"
            >
                <i class="fas fa-plus"></i>
                Add New Room
            </button>
        </div>
        @endif
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirm Delete</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete Room {{ $roomToDelete->room_code }}? This action cannot be undone and will also remove all associated images.</p>
            <div class="flex justify-end gap-3">
                <button wire:click="closeDeleteModal" class="px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Cancel</button>
                <button wire:click="delete({{ $roomToDelete->id }})" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">Delete Room</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Enhanced Image Viewer -->
    @if($viewerActive && $selectedRoom)
    <div class="fixed inset-0 gallery-viewer-overlay z-[1000] flex flex-col items-center justify-center p-4">
        <!-- Main Image Container -->
        <div class="relative w-full max-w-6xl flex-1 flex items-center justify-center mb-24">
            <!-- Main Image -->
            <div class="w-full h-full flex items-center justify-center">
                <img 
                    src="{{ Storage::url($selectedRoom->images[$currentImageIndex]->image_path) }}" 
                    alt="Room {{ $selectedRoom->room_code }} - Image {{ $currentImageIndex + 1 }}"
                    class="gallery-main-image rounded-lg shadow-2xl"
                    wire:loading.class="opacity-50"
                >
            </div>
        </div>

        <!-- Room Info Panel -->
        <div class="gallery-info-panel rounded-2xl p-6 text-white max-w-4xl w-full mx-auto">
            <div class="text-center mb-4">
                <h3 class="text-2xl font-bold mb-2">Room {{ $selectedRoom->room_code }}</h3>
                <p class="text-gray-300 mb-3">{{ ucfirst($selectedRoom->type) }} Room • ₱{{ number_format($selectedRoom->price, 2) }}/month</p>
                
                <!-- Image Counter -->
                <div class="flex items-center justify-center gap-4 mb-4">
                    <span class="text-sm text-gray-300">
                        {{ $currentImageIndex + 1 }} / {{ $selectedRoom->images->count() }}
                    </span>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-center gap-4 mb-4">
                    <button 
                        wire:click="prevImage" 
                        class="gallery-nav-btn text-white px-6 py-3 rounded-lg flex items-center gap-2 hover:bg-opacity-70 transition-colors font-medium"
                    >
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </button>
                    
                    <button 
                        wire:click="closeViewer" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors"
                    >
                        <i class="fas fa-times"></i>
                        Exit Gallery
                    </button>
                    
                    <button 
                        wire:click="nextImage" 
                        class="gallery-nav-btn text-white px-6 py-3 rounded-lg flex items-center gap-2 hover:bg-opacity-70 transition-colors font-medium"
                    >
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Thumbnail Navigation -->
                @if($selectedRoom->images->count() > 1)
                <div class="flex gap-2 justify-center overflow-x-auto pb-2 max-w-2xl mx-auto">
                    @foreach($selectedRoom->images as $index => $image)
                    <button 
                        wire:click="setImage({{ $index }})"
                        class="flex-shrink-0 rounded-lg overflow-hidden border-2 transition-all {{ $index === $currentImageIndex ? 'border-indigo-500 scale-110 shadow-lg' : 'border-transparent opacity-60 hover:opacity-100 hover:scale-105' }}"
                        title="Go to image {{ $index + 1 }}"
                    >
                        <img 
                            src="{{ Storage::url($image->image_path) }}" 
                            alt="Thumbnail {{ $index + 1 }}"
                            class="gallery-thumbnail"
                        >
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Keyboard Navigation Script -->
    <script>
        document.addEventListener('keydown', function(e) {
            if (@this.get('viewerActive')) {
                if (e.key === 'ArrowLeft') {
                    @this.call('prevImage');
                    e.preventDefault();
                } else if (e.key === 'ArrowRight') {
                    @this.call('nextImage');
                    e.preventDefault();
                } else if (e.key === 'Escape') {
                    @this.call('closeViewer');
                    e.preventDefault();
                }
            }
        });

        // Prevent body scroll when viewer is active
        Livewire.hook('component.init', ({ component }) => {
            if (component.name === 'admin.rooms') {
                Livewire.hook('element.updated', (el, component) => {
                    if (component.name === 'admin.rooms') {
                        const viewerActive = @this.get('viewerActive');
                        if (viewerActive) {
                            document.body.classList.add('gallery-viewer-active');
                        } else {
                            document.body.classList.remove('gallery-viewer-active');
                        }
                    }
                });
            }
        });

        // Handle alert messages from Livewire
        Livewire.on('alert', (data) => {
            alert(data.message);
        });
    </script>
    @endif
</div>