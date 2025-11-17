<div>
    <!-- Header Section -->
    <div class="header-section">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-white">Room Details</h2>
                <p class="text-white opacity-90">View complete room information</p>
            </div>
            
            <!-- Back Button -->
            <div class="flex gap-3">
                <a 
                    href="{{ route('student.room') }}" 
                    class="bg-white text-gray-700 hover:bg-gray-100 px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-all shadow-lg"
                >
                    <i class="fas fa-arrow-left"></i>
                    Back to Rooms
                </a>
            </div>
        </div>
    </div>

    <!-- Room Information Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 mb-8 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-info-circle mr-2"></i>
                Room {{ $roomDetails['room_code'] }} - Details
            </h3>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Images Section -->
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Room Images
                </label>
                
                @if(!empty($roomDetails['images']))
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($roomDetails['images'] as $index => $image)
                    <div class="relative group cursor-pointer" wire:click="openViewer">
                        <img 
                            src="{{ Storage::url($image['image_path']) }}" 
                            class="w-full h-24 object-cover rounded-lg shadow-sm hover:shadow-md transition-all"
                            alt="Room image {{ $index + 1 }}"
                        >
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all flex items-center justify-center">
                            <div class="text-white opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all">
                                <i class="fas fa-expand text-lg"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
                    <i class="fas fa-image text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400">No images available for this room</p>
                </div>
                @endif
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        Room Code
                    </label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $roomDetails['room_code'] }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        Room Type
                    </label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white capitalize">
                        {{ $roomDetails['type'] }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        Status
                    </label>
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium 
                        @if($roomDetails['status'] === 'available') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @elseif($roomDetails['status'] === 'occupied') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                        {{ ucfirst($roomDetails['status']) }}
                    </span>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        Price per Month
                    </label>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        ₱{{ number_format($roomDetails['price'], 2) }}
                    </p>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ $roomDetails['description'] ?: 'No description provided.' }}
                </p>
            </div>

            <!-- Amenities Section -->
            @if(!empty($roomDetails['amenities']))
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Amenities
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach($roomDetails['amenities'] as $amenity)
                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-2 rounded-full text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-check text-xs"></i>
                        {{ $amenity }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                <a 
                    href="{{ route('student.room') }}" 
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-center"
                >
                    <i class="fas fa-home"></i>
                    Back to Available Rooms
                </a>
            </div>
        </div>
    </div>

    <!-- Image Viewer Modal -->
    @if($viewerActive && !empty($roomDetails['images']))
    <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex flex-col items-center justify-center p-4">
        <!-- Main Image Container -->
        <div class="relative w-full max-w-6xl flex-1 flex items-center justify-center mb-24">
            <!-- Main Image -->
            <div class="w-full h-full flex items-center justify-center">
                <img 
                    src="{{ Storage::url($roomDetails['images'][$currentImageIndex]['image_path']) }}" 
                    alt="Room {{ $roomDetails['room_code'] }} - Image {{ $currentImageIndex + 1 }}"
                    class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                >
            </div>
        </div>

        <!-- Room Info Panel -->
        <div class="bg-gray-800 rounded-2xl p-6 text-white max-w-4xl w-full mx-auto">
            <div class="text-center mb-4">
                <h3 class="text-2xl font-bold mb-2">Room {{ $roomDetails['room_code'] }}</h3>
                <p class="text-gray-300 mb-3">{{ ucfirst($roomDetails['type']) }} Room • ₱{{ number_format($roomDetails['price'], 2) }}/month</p>
                
                <!-- Image Counter -->
                <div class="flex items-center justify-center gap-4 mb-4">
                    <span class="text-sm text-gray-300">
                        {{ $currentImageIndex + 1 }} / {{ count($roomDetails['images']) }}
                    </span>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-center gap-4 mb-4">
                    <button 
                        wire:click="prevImage" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors font-medium"
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
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors font-medium"
                    >
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Thumbnail Navigation -->
                @if(count($roomDetails['images']) > 1)
                <div class="flex gap-2 justify-center overflow-x-auto pb-2 max-w-2xl mx-auto">
                    @foreach($roomDetails['images'] as $index => $image)
                    <button 
                        wire:click="setImage({{ $index }})"
                        class="flex-shrink-0 rounded-lg overflow-hidden border-2 transition-all {{ $index === $currentImageIndex ? 'border-indigo-500 scale-110 shadow-lg' : 'border-transparent opacity-60 hover:opacity-100 hover:scale-105' }}"
                        title="Go to image {{ $index + 1 }}"
                    >
                        <img 
                            src="{{ Storage::url($image['image_path']) }}" 
                            alt="Thumbnail {{ $index + 1 }}"
                            class="w-16 h-16 object-cover"
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
    </script>
    @endif
</div>