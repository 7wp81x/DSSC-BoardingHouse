<div class="max-w-7xl mx-auto space-y-12">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl mb-6">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- MY CURRENT ROOM -->
    <div class="header-section">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-white">Available Rooms</h2>
                <p class="text-white opacity-90">Browse and book available rooms</p>
            </div>
        </div>
    </div>

    <!-- AVAILABLE ROOMS -->
    @if(!empty($availableRooms['available']))
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($availableRooms['available'] as $room)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden group hover:shadow-xl transition-all duration-300">
                        <!-- Image Section -->
                        <div class="room-image-container relative overflow-hidden">
                            @if($room['images'] && count($room['images']) > 0)
                                <img 
                                    src="{{ Storage::url($room['images'][0]['image_path']) }}" 
                                    alt="Room {{ $room['room_code'] }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                                @if(count($room['images']) > 1)
                                <div class="absolute top-3 right-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs backdrop-blur-sm">
                                    <i class="fas fa-images mr-1"></i>{{ count($room['images']) }}
                                </div>
                                @endif
                                <div class="absolute bottom-3 left-3">
                                    <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        {{ ucfirst($room['type']) }}
                                    </span>
                                </div>
                                <!-- View Gallery Button -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                                    <button 
                                        wire:click="openViewer({{ $room['id'] }})"
                                        class="view-gallery-btn flex items-center gap-2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-lg hover:bg-opacity-70 transition-colors"
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
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Room {{ $room['room_code'] }}</h3>
                                <span class="text-2xl font-bold text-indigo-600">₱{{ number_format($room['price'], 2) }}</span>
                            </div>

                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                {{ $room['description'] ?: 'No description provided.' }}
                            </p>

                            <!-- Status -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Available
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">per month</span>
                            </div>

                            <!-- Amenities Preview -->
                            @if($room['amenities'] && count($room['amenities']) > 0)
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach(array_slice($room['amenities'], 0, 3) as $amenity)
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs">
                                    {{ $amenity }}
                                </span>
                                @endforeach
                                @if(count($room['amenities']) > 3)
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-1 rounded text-xs">
                                    +{{ count($room['amenities']) - 3 }} more
                                </span>
                                @endif
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                                <button 
                                    wire:click="requestBoarding({{ $room['id'] }})"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    @if($myRoom) disabled @endif
                                >
                                    <i class="fas fa-home"></i>
                                    Request Boarding
                                </button>
                                <a 
                                    href="{{ route('student.room.view', $room['id']) }}"
                                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                                >
                                    <i class="fas fa-info-circle"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- OCCUPIED ROOMS -->
    @if(!empty($availableRooms['occupied'] ?? []))
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-4">Occupied Rooms</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($availableRooms['occupied'] as $room)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden group">
                    <!-- Image Section -->
                    <div class="room-image-container relative overflow-hidden">
                        @if($room['images'] && count($room['images']) > 0)
                            <img 
                                src="{{ Storage::url($room['images'][0]['image_path']) }}" 
                                alt="Room {{ $room['room_code'] }}"
                                class="w-full h-48 object-cover"
                            >
                            @if(count($room['images']) > 1)
                            <div class="absolute top-3 right-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs backdrop-blur-sm">
                                <i class="fas fa-images mr-1"></i>{{ count($room['images']) }}
                            </div>
                            @endif
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($room['type']) }}
                                </span>
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
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Room {{ $room['room_code'] }}</h3>
                            <span class="text-2xl font-bold text-indigo-600">₱{{ number_format($room['price'], 2) }}</span>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                Occupied
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">per month</span>
                        </div>

                        <!-- Current Students -->
                        @if(!empty($room['current_students']))
                        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Current Boarders ({{ count($room['current_students']) }})</h4>
                            <div class="space-y-1 max-h-20 overflow-y-auto">
                                @foreach($room['current_students'] as $student)
                                <div class="flex items-center gap-2 text-xs text-blue-700 dark:text-blue-300">
                                    <div class="w-6 h-6 rounded-full bg-blue-200 dark:bg-blue-800 flex items-center justify-center text-blue-800 dark:text-blue-200 font-medium">
                                        {{ Str::substr($student['name'], 0, 1) }}
                                    </div>
                                    <span>{{ $student['name'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Amenities Preview -->
                        @if($room['amenities'] && count($room['amenities']) > 0)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($room['amenities'], 0, 3) as $amenity)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs">
                                {{ $amenity }}
                            </span>
                            @endforeach
                            @if(count($room['amenities']) > 3)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-1 rounded text-xs">
                                +{{ count($room['amenities']) - 3 }} more
                            </span>
                            @endif
                        </div>
                        @endif

                        <!-- Actions for Occupied Rooms - Show Request Boarding button -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <button 
                                wire:click="requestBoarding({{ $room['id'] }})"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                @if($myRoom) disabled @endif
                            >
                                <i class="fas fa-home"></i>
                                Request Boarding
                            </button>
                            <a 
                                href="{{ route('student.room.view', $room['id']) }}"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-info-circle"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- FULL ROOMS -->
    @if(!empty($availableRooms['full'] ?? []))
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-4">Full Rooms</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($availableRooms['full'] as $room)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden group">
                    <!-- Image Section -->
                    <div class="room-image-container relative overflow-hidden">
                        @if($room['images'] && count($room['images']) > 0)
                            <img 
                                src="{{ Storage::url($room['images'][0]['image_path']) }}" 
                                alt="Room {{ $room['room_code'] }}"
                                class="w-full h-48 object-cover"
                            >
                            @if(count($room['images']) > 1)
                            <div class="absolute top-3 right-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs backdrop-blur-sm">
                                <i class="fas fa-images mr-1"></i>{{ count($room['images']) }}
                            </div>
                            @endif
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($room['type']) }}
                                </span>
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
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Room {{ $room['room_code'] }}</h3>
                            <span class="text-2xl font-bold text-indigo-600">₱{{ number_format($room['price'], 2) }}</span>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                Full
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">per month</span>
                        </div>

                        <!-- Current Students -->
                        @if(!empty($room['current_students']))
                        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <h4 class="text-sm font-medium text-red-900 dark:text-red-100 mb-2">Current Boarders ({{ count($room['current_students']) }})</h4>
                            <div class="space-y-1 max-h-20 overflow-y-auto">
                                @foreach($room['current_students'] as $student)
                                <div class="flex items-center gap-2 text-xs text-red-700 dark:text-red-300">
                                    <div class="w-6 h-6 rounded-full bg-red-200 dark:bg-red-800 flex items-center justify-center text-red-800 dark:text-red-200 font-medium">
                                        {{ Str::substr($student['name'], 0, 1) }}
                                    </div>
                                    <span>{{ $student['name'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Amenities Preview -->
                        @if($room['amenities'] && count($room['amenities']) > 0)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($room['amenities'], 0, 3) as $amenity)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs">
                                {{ $amenity }}
                            </span>
                            @endforeach
                            @if(count($room['amenities']) > 3)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-1 rounded text-xs">
                                +{{ count($room['amenities']) - 3 }} more
                            </span>
                            @endif
                        </div>
                        @endif

                        <!-- Disabled Request Boarding Button for Full Rooms -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <button 
                                class="flex-1 bg-gray-400 text-white py-2 px-4 rounded-lg font-medium text-sm flex items-center justify-center gap-2 cursor-not-allowed opacity-50"
                                disabled
                            >
                                <i class="fas fa-home"></i>
                                Room Full
                            </button>
                            <a 
                                href="{{ route('student.room.view', $room['id']) }}"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-info-circle"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- MAINTENANCE ROOMS -->
    @if(!empty($availableRooms['maintenance'] ?? []))
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-4">Under Maintenance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($availableRooms['maintenance'] as $room)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden group">
                    <!-- Image Section -->
                    <div class="room-image-container relative overflow-hidden">
                        @if($room['images'] && count($room['images']) > 0)
                            <img 
                                src="{{ Storage::url($room['images'][0]['image_path']) }}" 
                                alt="Room {{ $room['room_code'] }}"
                                class="w-full h-48 object-cover"
                            >
                            @if(count($room['images']) > 1)
                            <div class="absolute top-3 right-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs backdrop-blur-sm">
                                <i class="fas fa-images mr-1"></i>{{ count($room['images']) }}
                            </div>
                            @endif
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($room['type']) }}
                                </span>
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
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Room {{ $room['room_code'] }}</h3>
                            <span class="text-2xl font-bold text-indigo-600">₱{{ number_format($room['price'], 2) }}</span>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Maintenance
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">per month</span>
                        </div>

                        <!-- Amenities Preview -->
                        @if($room['amenities'] && count($room['amenities']) > 0)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($room['amenities'], 0, 3) as $amenity)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs">
                                {{ $amenity }}
                            </span>
                            @endforeach
                            @if(count($room['amenities']) > 3)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-1 rounded text-xs">
                                +{{ count($room['amenities']) - 3 }} more
                            </span>
                            @endif
                        </div>
                        @endif

                        <!-- Actions for Maintenance Rooms - Show Request Boarding button -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <button 
                                wire:click="requestBoarding({{ $room['id'] }})"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                @if($myRoom) disabled @endif
                            >
                                <i class="fas fa-home"></i>
                                Request Boarding
                            </button>
                            <a 
                                href="{{ route('student.room.view', $room['id']) }}"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-info-circle"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Image Viewer Modal - Fixed for large images -->
    @if($viewerActive && $selectedRoom)
    <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex flex-col items-center justify-center p-4">
        <!-- Main Image Container -->
        <div class="relative w-full max-w-6xl flex-1 flex items-center justify-center mb-24">
            <!-- Main Image -->
            <div class="w-full h-full flex items-center justify-center">
                <img 
                    src="{{ Storage::url($selectedRoom['images'][$currentImageIndex]['image_path']) }}" 
                    alt="Room {{ $selectedRoom['room_code'] }} - Image {{ $currentImageIndex + 1 }}"
                    class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-2xl"
                    style="max-width: 90vw; max-height: 70vh;"
                    wire:loading.class="opacity-50"
                >
            </div>
        </div>

        <!-- Room Info Panel -->
        <div class="bg-gray-800 rounded-2xl p-6 text-white max-w-4xl w-full mx-auto">
            <div class="text-center mb-4">
                <h3 class="text-2xl font-bold mb-2">Room {{ $selectedRoom['room_code'] }}</h3>
                <p class="text-gray-300 mb-3">{{ ucfirst($selectedRoom['type']) }} Room • ₱{{ number_format($selectedRoom['price'], 2) }}/month</p>
                
                <!-- Image Counter -->
                <div class="flex items-center justify-center gap-4 mb-4">
                    <span class="text-sm text-gray-300">
                        {{ $currentImageIndex + 1 }} / {{ count($selectedRoom['images']) }}
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
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors"
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
                @if(count($selectedRoom['images']) > 1)
                <div class="flex gap-2 justify-center overflow-x-auto pb-2 max-w-2xl mx-auto">
                    @foreach($selectedRoom['images'] as $index => $image)
                    <button 
                        wire:click="setImage({{ $index }})"
                        class="flex-shrink-0 rounded-lg overflow-hidden border-2 transition-all {{ $index === $currentImageIndex ? 'border-indigo-500 scale-110 shadow-lg' : 'border-transparent opacity-60 hover:opacity-100 hover:scale-105' }}"
                        title="Go to image {{ $index + 1 }}"
                    >
                        <img 
                            src="{{ Storage::url($image['image_path']) }}" 
                            alt="Thumbnail {{ $index + 1 }}"
                            class="w-16 h-16 object-cover"
                            loading="lazy"
                        >
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>