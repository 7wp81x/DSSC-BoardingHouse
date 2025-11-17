<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Student Dorm</title>
    
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
        .pricing-card:hover {
            transform: translateY(-5px);
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
                    <a href="{{ route('rooms.public') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Rooms</a>
                    <a href="{{ route('pricing.public') }}" class="text-indigo-600 font-medium transition">Pricing</a>
                    <a href="{{ route('contact.public') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Contact</a>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Transparent Pricing</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Choose the perfect room that fits your budget and lifestyle. All prices include utilities and amenities.
                    </p>
                </div>
            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                @php
                    $roomTypeData = [
                        'single' => ['icon' => 'fas fa-user', 'color' => 'blue', 'description' => 'Perfect for students who prefer privacy and quiet study time'],
                        'twin' => ['icon' => 'fas fa-user-friends', 'color' => 'green', 'description' => 'Great for friends or making new ones with one roommate'],
                        'quad' => ['icon' => 'fas fa-users', 'color' => 'purple', 'description' => 'Social living with 3 roommates, most economical option'],
                        'premium' => ['icon' => 'fas fa-crown', 'color' => 'yellow', 'description' => 'Luxury suite with extra space and premium amenities']
                    ];
                @endphp

                @foreach(['single', 'twin', 'quad', 'premium'] as $type)
                <div class="pricing-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition duration-300">
                    <div class="text-center mb-6">
                        @if(isset($roomTypes[$type]))
                            @php
                                $minPrice = $roomTypes[$type]->min_price;
                                $maxPrice = $roomTypes[$type]->max_price;
                                $avgPrice = $roomTypes[$type]->avg_price;
                                $roomCount = $roomTypes[$type]->room_count;
                            @endphp
                            <div class="w-16 h-16 bg-{{ $roomTypeData[$type]['color'] }}-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="{{ $roomTypeData[$type]['icon'] }} text-2xl text-{{ $roomTypeData[$type]['color'] }}-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 capitalize">{{ $type }} Room</h3>
                            
                            @if($minPrice == $maxPrice)
                                <div class="text-3xl font-bold text-{{ $roomTypeData[$type]['color'] }}-600 mb-2">
                                    ₱{{ number_format($minPrice) }}
                                </div>
                            @else
                                <div class="text-3xl font-bold text-{{ $roomTypeData[$type]['color'] }}-600 mb-2">
                                    ₱{{ number_format($minPrice) }} - ₱{{ number_format($maxPrice) }}
                                </div>
                            @endif
                            
                            <div class="text-gray-500 text-sm">per month</div>
                            <div class="mt-2">
                                <span class="bg-{{ $roomTypeData[$type]['color'] }}-100 text-{{ $roomTypeData[$type]['color'] }}-800 px-2 py-1 rounded-full text-xs">
                                    {{ $roomCount }} room{{ $roomCount > 1 ? 's' : '' }} available
                                </span>
                            </div>
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="{{ $roomTypeData[$type]['icon'] }} text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 capitalize">{{ $type }} Room</h3>
                            <div class="text-gray-400 text-lg mb-2">Currently Unavailable</div>
                        @endif
                    </div>
                    
                    <p class="text-gray-600 text-sm text-center mb-6">
                        {{ $roomTypeData[$type]['description'] }}
                    </p>

                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            High-speed WiFi
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Study Desk & Chair
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Comfortable Bed
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            24/7 Security
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Utilities Included
                        </li>
                        @if($type === 'premium')
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Private Bathroom
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Air Conditioning
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Mini Refrigerator
                        </li>
                        @endif
                    </ul>

                    @if(isset($roomTypes[$type]))
                    <a href="{{ route('rooms.public') }}?type={{ $type }}" 
                       class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                        View {{ $type === 'premium' ? 'Premium' : ucfirst($type) }} Rooms
                    </a>
                    @else
                    <button disabled 
                            class="block w-full bg-gray-300 text-gray-500 text-center py-3 rounded-lg font-medium cursor-not-allowed">
                        Currently Unavailable
                    </button>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Room Details by Type -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Room Details & Availability</h2>
                
                <div class="space-y-8">
                    @foreach(['single', 'twin', 'quad', 'premium'] as $type)
                    @if(isset($allRooms[$type]) && $allRooms[$type]->count() > 0)
                    <div class="border-b border-gray-200 pb-8 last:border-b-0 last:pb-0">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 capitalize">{{ $type }} Rooms</h3>
                            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $allRooms[$type]->count() }} room{{ $allRooms[$type]->count() > 1 ? 's' : '' }} available
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($allRooms[$type] as $room)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-semibold text-gray-900">{{ $room->room_code }}</h4>
                                    <span class="text-lg font-bold text-indigo-600">₱{{ number_format($room->price) }}</span>
                                </div>
                                
                                @if($room->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $room->description }}</p>
                                @endif
                                
                                @if($room->amenities && count($room->amenities) > 0)
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach(array_slice($room->amenities, 0, 3) as $amenity)
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                        {{ $amenity }}
                                    </span>
                                    @endforeach
                                    @if(count($room->amenities) > 3)
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                        +{{ count($room->amenities) - 3 }} more
                                    </span>
                                    @endif
                                </div>
                                @endif
                                
                                <a href="{{ route('rooms.public.show', $room) }}" 
                                   class="block w-full bg-indigo-600 text-white text-center py-2 rounded text-sm font-medium hover:bg-indigo-700 transition">
                                    View Details
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid md:grid-cols-3 gap-8 mt-12">
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Security Deposit</h3>
                    <p class="text-gray-600 text-sm">One month's rent refundable upon move-out</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice-dollar text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Utilities Included</h3>
                    <p class="text-gray-600 text-sm">Water, electricity, and internet included in rent</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-check text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Flexible Terms</h3>
                    <p class="text-gray-600 text-sm">6-month and 12-month lease options available</p>
                </div>
            </div>
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
                        <li><a href="{{ route('pricing.public') }}" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="{{ route('contact.public') }}" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i>
                            123 University Avenue
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
