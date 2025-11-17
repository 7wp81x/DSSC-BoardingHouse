<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms - Student Dorm</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .room-card:hover .room-image {
            transform: scale(1.05);
        }
        .no-image {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-weight: bold;
        }
        .filter-active {
            background-color: #4f46e5;
            color: white;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                        <i class="fas fa-home text-2xl text-indigo-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">StudentDorm</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}#features" class="text-gray-700 hover:text-indigo-600 font-medium transition">Features</a>
                    <a href="{{ route('home') }}#rooms" class="text-gray-700 hover:text-indigo-600 font-medium transition">Rooms</a>
                    <a href="{{ route('home') }}#pricing" class="text-gray-700 hover:text-indigo-600 font-medium transition">Pricing</a>
                    <a href="{{ route('home') }}#contact" class="text-gray-700 hover:text-indigo-600 font-medium transition">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition shadow-lg">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-16 min-h-screen">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Available Rooms</h1>
                        <p class="text-gray-600 mt-2">Find your perfect accommodation from our available rooms</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Filters -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-wrap gap-2">
                    <button class="filter-btn px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition" data-filter="all">
                        All Rooms
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition" data-filter="single">
                        Single
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition" data-filter="twin">
                        Twin
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition" data-filter="quad">
                        Quad
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition" data-filter="premium">
                        Premium
                    </button>
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if($rooms->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($rooms as $room)
                <div class="room-item bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-xl transition group" data-type="{{ $room->type }}">
                    <div class="relative overflow-hidden">
                        @if($room->images->count() > 0 && $room->images->first()->image_path)
                            <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" 
                                 alt="{{ $room->room_code }}" 
                                 class="room-image w-full h-48 object-cover transition duration-500">
                        @else
                            <div class="no-image w-full h-48">
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl mb-2"></i>
                                    <div>NO IMAGE</div>
                                </div>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                            <span class="text-indigo-600 font-bold">₱{{ number_format($room->price) }}/mo</span>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs font-medium capitalize">
                                {{ $room->type }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $room->room_code }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            {{ $room->description ?: 'Comfortable student accommodation with modern amenities.' }}
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 capitalize">
                                <i class="fas fa-user-friends mr-1"></i>
                                {{ $room->type === 'single' ? '1 person' : ($room->type === 'twin' ? '2 people' : ($room->type === 'quad' ? '4 people' : '2 people')) }}
                            </span>
                            <a href="{{ route('rooms.public.show', $room) }}" 
                               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <div class="bg-white rounded-2xl p-12 max-w-md mx-auto">
                    <i class="fas fa-bed text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-600 mb-2">No Rooms Available</h3>
                    <p class="text-gray-500 mb-6">All our rooms are currently occupied. Please check back later.</p>
                    <a href="{{ route('home') }}" 
                       class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                        Return to Home
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-home text-2xl text-indigo-400 mr-2"></i>
                        <span class="text-xl font-bold">StudentDorm</span>
                    </div>
                    <p class="text-gray-400">
                        Providing quality student accommodation with modern amenities and excellent service.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="{{ route('home') }}#rooms" class="hover:text-white transition">Rooms</a></li>
                        <li><a href="{{ route('home') }}#pricing" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="{{ route('home') }}#contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i>
                            University District, City
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2 text-indigo-400"></i>
                            +1 (555) 123-4567
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-indigo-400"></i>
                            hello@studentdorm.com
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 StudentDorm. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Room filtering
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const roomItems = document.querySelectorAll('.room-item');
            
            // Set first filter as active
            filterButtons[0].classList.add('filter-active');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('filter-active'));
                    this.classList.add('filter-active');
                    
                    // Filter rooms
                    roomItems.forEach(room => {
                        if (filter === 'all' || room.getAttribute('data-type') === filter) {
                            room.style.display = 'block';
                        } else {
                            room.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
