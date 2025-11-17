<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->room_code }} - Student Dorm</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .no-image {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-weight: bold;
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
                    <a href="{{ route('rooms.public') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">All Rooms</a>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-600">
                        <li>
                            <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
                        </li>
                        <li>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </li>
                        <li>
                            <a href="{{ route('rooms.public') }}" class="hover:text-indigo-600">All Rooms</a>
                        </li>
                        <li>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </li>
                        <li class="text-gray-900 font-medium">{{ $room->room_code }}</li>
                    </ol>
                </nav>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Room Images -->
                <div>
                    @if($room->images->count() > 0)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-200">
                            <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" 
                                 alt="{{ $room->room_code }}" 
                                 class="w-full h-96 object-cover">
                        </div>
                        @if($room->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2 mt-4">
                            @foreach($room->images as $image)
                            <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $room->room_code }}" 
                                     class="w-full h-20 object-cover">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-200">
                            <div class="no-image w-full h-96">
                                <div class="text-center">
                                    <i class="fas fa-image text-6xl mb-4"></i>
                                    <div class="text-xl">NO IMAGE AVAILABLE</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Room Details -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium capitalize mb-2 inline-block">
                                {{ $room->type }} Room
                            </span>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $room->room_code }}</h1>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-indigo-600">₱{{ number_format($room->price) }}/mo</div>
                            <div class="text-sm text-gray-500">per month</div>
                        </div>
                    </div>

                    <p class="text-gray-600 mb-6 leading-relaxed">
                        {{ $room->description ?: 'Comfortable and well-maintained student accommodation with all necessary amenities for a productive academic life.' }}
                    </p>

                    <!-- Room Features -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Room Features</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <i class="fas fa-user-friends text-indigo-500 mr-3"></i>
                                <span class="text-gray-700">
                                    @switch($room->type)
                                        @case('single')
                                            1 Person
                                            @break
                                        @case('twin')
                                            2 People
                                            @break
                                        @case('quad')
                                            4 People
                                            @break
                                        @case('premium')
                                            2 People
                                            @break
                                    @endswitch
                                </span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-bed text-indigo-500 mr-3"></i>
                                <span class="text-gray-700">Comfortable Bed</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-desktop text-indigo-500 mr-3"></i>
                                <span class="text-gray-700">Study Desk</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-wifi text-indigo-500 mr-3"></i>
                                <span class="text-gray-700">High-speed WiFi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    @if($room->amenities && count($room->amenities) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Amenities</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($room->amenities as $amenity)
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                {{ $amenity }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <a href="{{ route('register') }}" 
                           class="w-full bg-indigo-600 text-white px-6 py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition text-center block">
                            Book This Room
                        </a>
                        <a href="{{ route('rooms.public') }}" 
                           class="w-full border-2 border-gray-300 text-gray-700 px-6 py-4 rounded-lg font-bold text-lg hover:bg-gray-50 transition text-center block">
                            View Other Rooms
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Rooms -->
            @if($relatedRooms->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Rooms</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedRooms as $relatedRoom)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-xl transition">
                        <div class="relative overflow-hidden">
                            @if($relatedRoom->images->count() > 0 && $relatedRoom->images->first()->image_path)
                                <img src="{{ asset('storage/' . $relatedRoom->images->first()->image_path) }}" 
                                     alt="{{ $relatedRoom->room_code }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="no-image w-full h-48">
                                    <div class="text-center">
                                        <i class="fas fa-image text-2xl mb-1"></i>
                                        <div class="text-sm">NO IMAGE</div>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                <span class="text-indigo-600 font-bold">₱{{ number_format($relatedRoom->price) }}/mo</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $relatedRoom->room_code }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">
                                {{ $relatedRoom->description ?: 'Comfortable student accommodation' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 capitalize">
                                    {{ $relatedRoom->type }} room
                                </span>
                                <a href="{{ route('rooms.public.show', $relatedRoom) }}" 
                                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
                        <li><a href="{{ route('rooms.public') }}" class="hover:text-white transition">All Rooms</a></li>
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
</body>
</html>
