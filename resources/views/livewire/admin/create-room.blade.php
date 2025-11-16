<div>
    <!-- Header Section -->
    <div class="header-section">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-white">Add New Room</h2>
                <p class="text-white opacity-90">Create a new room listing</p>
            </div>
            
            <!-- Back Button -->
            <div class="flex gap-3">
                <a 
                    href="{{ route('admin.rooms') }}" 
                    class="bg-white text-gray-700 hover:bg-gray-100 px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-all shadow-lg"
                >
                    <i class="fas fa-arrow-left"></i>
                    Back to Rooms
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Room Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 mb-8 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-plus mr-2"></i>
                Add New Room
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
            </div>

            <!-- Amenities Section -->
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

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-600 form-actions">
                <button 
                    type="submit" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled"
                    wire:target="save"
                >
                    <i class="fas fa-check"></i>
                    <span wire:loading.remove wire:target="save">
                        Create Room
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="fas fa-spinner fa-spin"></i>
                        Creating...
                    </span>
                </button>
                <a 
                    href="{{ route('admin.rooms') }}" 
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-center"
                >
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>