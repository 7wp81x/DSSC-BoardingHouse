<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSSC Dorm - Comfortable & Affordable Student Accommodation</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Your Existing CSS -->
    <link href="{{ asset('css/admin.styles.css') }}" rel="stylesheet">
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #8b5cf6 100%);
        }
        .room-card:hover .room-image {
            transform: scale(1.05);
        }
        .stat-number {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
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
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-home text-2xl text-indigo-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">DSSC Dorm</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 font-medium transition">Features</a>
                    <a href="#rooms" class="text-gray-700 hover:text-indigo-600 font-medium transition">Rooms</a>
                    <a href="#pricing" class="text-gray-700 hover:text-indigo-600 font-medium transition">Pricing</a>
                    <a href="#contact" class="text-gray-700 hover:text-indigo-600 font-medium transition">Contact</a>
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

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-white">
                    <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6">
                        Comfortable Student 
                        <span class="text-yellow-300">Living</span> 
                        Made Easy
                    </h1>
                    <p class="text-xl text-indigo-100 mb-8 leading-relaxed">
                        Experience premium student accommodation with modern amenities, 
                        secure environment, and hassle-free management. Your perfect 
                        home away from home.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" 
                           class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-2xl text-center">
                            Book Your Room Now
                        </a>
                        <a href="#rooms" 
                           class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-indigo-600 transition text-center">
                            Browse Rooms
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 mt-12">
                        <div class="text-center">
                            <div class="stat-number text-3xl font-bold">100+</div>
                            <div class="text-indigo-200 text-sm">Happy Students</div>
                        </div>
                        <div class="text-center">
                            <div class="stat-number text-3xl font-bold">24/7</div>
                            <div class="text-indigo-200 text-sm">Support</div>
                        </div>
                        <div class="text-center">
                            <div class="stat-number text-3xl font-bold">4.9★</div>
                            <div class="text-indigo-200 text-sm">Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20">
                        <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Modern Student Room" 
                             class="rounded-xl shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Our Dorm?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We provide everything you need for a comfortable and productive student life
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-wifi text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">High-Speed Internet</h3>
                    <p class="text-gray-600">Reliable fiber internet for your studies and entertainment needs.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">24/7 Security</h3>
                    <p class="text-gray-600">CCTV surveillance and security personnel for your safety.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-credit-card text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Easy Payments</h3>
                    <p class="text-gray-600">Multiple payment methods with online tracking and receipts.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Available Rooms</h2>
                <p class="text-xl text-gray-600">Find your perfect space from our variety of room types</p>
            </div>

            @if($rooms->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($rooms as $room)
                <div class="room-card bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-xl transition group">
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
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $room->room_code }}</h3>
                        <p class="text-gray-600 mb-4">{{ $room->description ?: 'Comfortable student accommodation' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 capitalize">
                                <i class="fas fa-user-friends mr-1"></i>
                                {{ $room->type }} room
                            </span>
                            <a href="{{ route('register') }}" 
                               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <div class="bg-gray-50 rounded-2xl p-12">
                    <i class="fas fa-bed text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-600 mb-2">No Rooms Available</h3>
                    <p class="text-gray-500">Check back later for new room listings.</p>
                </div>
            </div>
            @endif

            <div class="text-center mt-12">
                <a href="{{ route('rooms.public') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-lg font-bold hover:bg-indigo-600 hover:text-white transition">
                    View All Rooms
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Transparent Pricing</h2>
                <p class="text-xl text-gray-600">Choose the room that fits your budget and lifestyle</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $roomTypeData = [
                        'single' => ['icon' => 'fas fa-user', 'color' => 'blue', 'description' => 'Perfect for students who prefer privacy and quiet study time'],
                        'twin' => ['icon' => 'fas fa-user-friends', 'color' => 'green', 'description' => 'Great for friends or making new ones with one roommate'],
                        'quad' => ['icon' => 'fas fa-users', 'color' => 'purple', 'description' => 'Social living with 3 roommates, most economical option'],
                        'premium' => ['icon' => 'fas fa-crown', 'color' => 'yellow', 'description' => 'Luxury suite with extra space and premium amenities']
                    ];
                @endphp

                @foreach(['single', 'twin', 'quad', 'premium'] as $type)
                <div class="pricing-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 transition duration-300">
                    <div class="text-center mb-6">
                        @if(isset($roomTypes[$type]))
                            @php
                                $minPrice = $roomTypes[$type]->min_price;
                                $roomCount = $roomTypes[$type]->room_count;
                            @endphp
                            <div class="w-16 h-16 bg-{{ $roomTypeData[$type]['color'] }}-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="{{ $roomTypeData[$type]['icon'] }} text-2xl text-{{ $roomTypeData[$type]['color'] }}-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 capitalize">{{ $type }} Room</h3>
                            <div class="text-3xl font-bold text-{{ $roomTypeData[$type]['color'] }}-600 mb-2">
                                ₱{{ number_format($minPrice) }}
                            </div>
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
                        @if($type === 'premium')
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Private Bathroom
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Air Conditioning
                        </li>
                        @endif
                    </ul>

                    <a href="{{ route('rooms.public') }}?type={{ $type }}" 
                       class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                        View {{ $type === 'premium' ? 'Premium' : ucfirst($type) }} Rooms
                    </a>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('pricing.public') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-lg font-bold hover:bg-indigo-600 hover:text-white transition">
                    Detailed Pricing
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
                <p class="text-xl text-gray-600">Have questions? We're here to help you find your perfect room</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Visit Us</h4>
                                <p class="text-gray-600">Davao del Sur State College Main Campus
<br>Matti, Digos City, Davao del Sur, Philippines</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Call Us</h4>
                                <p class="text-gray-600">+1 (555) 123-4567<br>Mon-Fri from 8am to 6pm</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Email Us</h4>
                                <p class="text-gray-600">hello@studentdorm.com<br>We reply within 24 hours</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Office Hours</h4>
                                <p class="text-gray-600">Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 2:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>
                    
                    <form action="{{ route('inquiry.submit') }}" method="POST">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="name" name="name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone" name="phone" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="room_type" class="block text-sm font-medium text-gray-700 mb-1">Room Type Interested In</label>
                            <select id="room_type" name="room_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Any Room Type</option>
                                <option value="single">Single Room</option>
                                <option value="twin">Twin Sharing</option>
                                <option value="quad">Quad Room</option>
                                <option value="premium">Premium Suite</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea id="message" name="message" rows="4" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Tell us about your accommodation needs..."></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-indigo-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition">
                            Send Message
                        </button>
                    </form>

                    @if(session('success'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Find Your New Home?
            </h2>
            <p class="text-xl text-indigo-100 mb-8">
                Join hundreds of satisfied students who found their perfect accommodation with us.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-2xl">
                    Get Started Today
                </a>
                <a href="#contact" 
                   class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-indigo-600 transition">
                    Have Questions?
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-home text-2xl text-indigo-400 mr-2"></i>
                        <span class="text-xl font-bold">DSSC Dorm</span>
                    </div>
                    <p class="text-gray-400">
                        Providing quality student accommodation with modern amenities and excellent service.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#rooms" class="hover:text-white transition">Rooms</a></li>
                        <li><a href="#pricing" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
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
                <p>&copy; 2024 DSSC Dorm. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
