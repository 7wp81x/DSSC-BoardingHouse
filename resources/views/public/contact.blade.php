<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Student Dorm</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <a href="{{ route('pricing.public') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">Pricing</a>
                    <a href="{{ route('contact.public') }}" class="text-indigo-600 font-medium transition">Contact</a>
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
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Get in touch with us. We're here to help you find your perfect student accommodation.
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Get In Touch</h2>
                    <p class="text-gray-600 mb-8">
                        Have questions about our rooms, pricing, or availability? We're here to help you 
                        find the perfect student accommodation that fits your needs and budget.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Visit Our Office</h4>
                                <p class="text-gray-600">123 University Avenue<br>Student District, City 1000<br>Philippines</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Call Us</h4>
                                <p class="text-gray-600">+63 (2) 8123-4567<br>+63 (917) 123-4567 (Globe)<br>+63 (905) 123-4567 (Smart)</p>
                                <p class="text-gray-500 text-sm mt-1">Mon-Fri from 8am to 6pm, Sat 9am-2pm</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Email Us</h4>
                                <p class="text-gray-600">hello@studentdorm.com<br>bookings@studentdorm.com<br>support@studentdorm.com</p>
                                <p class="text-gray-500 text-sm mt-1">We reply within 24 hours</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Office Hours</h4>
                                <p class="text-gray-600">Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 2:00 PM<br>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Follow Us on Social Media</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-700 transition">
                                <i class="fab fa-facebook-f text-white"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center hover:bg-blue-500 transition">
                                <i class="fab fa-twitter text-white"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-pink-600 rounded-lg flex items-center justify-center hover:bg-pink-700 transition">
                                <i class="fab fa-instagram text-white"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center hover:bg-red-700 transition">
                                <i class="fab fa-youtube text-white"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>
                    
                    <form action="{{ route('inquiry.submit') }}" method="POST">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="name" name="name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Your full name">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="your.email@example.com">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="+63 912 345 6789">
                        </div>

                        <div class="mb-4">
                            <label for="room_type" class="block text-sm font-medium text-gray-700 mb-1">Room Type Interested In</label>
                            <select id="room_type" name="room_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select room type (optional)</option>
                                <option value="single">Single Room</option>
                                <option value="twin">Twin Sharing</option>
                                <option value="quad">Quad Room</option>
                                <option value="premium">Premium Suite</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                            <textarea id="message" name="message" rows="5" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Tell us about your accommodation needs, preferred move-in date, budget, or any specific requirements..."></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-indigo-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition">
                            Send Message
                        </button>

                        <p class="text-gray-500 text-sm mt-3 text-center">
                            * Required fields. We'll get back to you within 24 hours.
                        </p>
                    </form>

                    @if(session('success'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Please check the form for errors and try again.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Map Section -->
            <div class="mt-16 bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Visit Our Location</h3>
                        <p class="text-gray-600 mb-6">
                            Our office is conveniently located in the University District, easily accessible 
                            by public transportation. Feel free to visit us during office hours for a 
                            personalized tour of our available rooms.
                        </p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-bus text-indigo-600 mr-3 w-5"></i>
                                <span class="text-gray-700">Bus routes: 12, 15, 23, 45</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-train text-indigo-600 mr-3 w-5"></i>
                                <span class="text-gray-700">Nearest MRT: University Station (500m)</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-car text-indigo-600 mr-3 w-5"></i>
                                <span class="text-gray-700">Free parking available for visitors</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-200 flex items-center justify-center min-h-64">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-map text-4xl mb-2"></i>
                            <p>Interactive Map</p>
                            <p class="text-sm">(Map integration can be added here)</p>
                        </div>
                    </div>
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
                            +63 (2) 8123-4567
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
