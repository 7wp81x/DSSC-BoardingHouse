<div class="@if($viewerActive) viewer-active @endif">
    <!-- Search Bar - Hidden when gallery viewer is active -->
    @if(!$viewerActive)
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

    <!-- Header Section - Also hidden when gallery viewer is active -->
    @if(!$viewerActive)
    <div class="header-section">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-white">Manage Rooms</h2>
                <p class="text-white opacity-90">View and manage all room listings</p>
            </div>
            
            <!-- Add New Room Button -->
            <div class="flex gap-3">
                <a 
                    href="{{ route('admin.rooms.create') }}" 
                    class="bg-white text-indigo-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-all shadow-lg"
                >
                    <i class="fas fa-plus"></i>
                    Add New Room
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages - Hidden when gallery viewer is active -->
    @if(!$viewerActive)
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
    @endif

    @if(!$viewerActive)
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
                    <a 
                        href="{{ route('admin.rooms.edit', $room->id) }}" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
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
            <a 
                href="{{ route('admin.rooms.create') }}" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 mx-auto transition-colors"
            >
                <i class="fas fa-plus"></i>
                Add New Room
            </a>
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
    </script>
    @endif
</div>