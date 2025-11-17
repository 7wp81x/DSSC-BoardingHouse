<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DSSC Dorm</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .auth-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #8b5cf6 100%);
        }
        .form-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
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
    <section class="auth-gradient min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                        Welcome 
                        <span class="text-yellow-300">Back</span>
                    </h1>
                    <p class="text-xl text-indigo-100 mb-8 leading-relaxed">
                        Sign in to your account to manage your bookings, make payments, and access exclusive dssc dorm features.
                    </p>
                    
                    <!-- Testimonial -->
                    <div class="bg-white/10 rounded-2xl p-6 mt-8 border border-white/20">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-yellow-300 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-quote-left text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Maria Santos</h4>
                                <div class="flex text-yellow-300">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-indigo-100 italic">
                            "DSSC Dorm made finding accommodation so easy! The online portal is simple to use and the support team is always helpful."
                        </p>
                    </div>
                </div>

                <!-- Login Form -->
                <div class="form-card rounded-2xl p-8 shadow-2xl">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-white mb-2">Sign In</h2>
                        <p class="text-indigo-100">Access your student dashboard</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                    <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-200 text-sm">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-white mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-indigo-300"></i>
                                </div>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus 
                                       autocomplete="email"
                                       class="input-field w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent"
                                       placeholder="Enter your email">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-indigo-300"></i>
                                </div>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       required 
                                       autocomplete="current-password"
                                       class="input-field w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent"
                                       placeholder="Enter your password">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" 
                                       type="checkbox" 
                                       name="remember"
                                       class="rounded bg-white/10 border-white/20 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-white">
                                <span class="ms-2 text-sm text-white">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-indigo-200 hover:text-white text-sm font-medium transition" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a class="text-indigo-200 hover:text-white text-sm font-medium transition" href="{{ route('register') }}">
                                Don't have an account?
                            </a>

                            <button type="submit" 
                                    class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg flex items-center">
                                Sign In
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Add some interactive elements
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-white', 'ring-opacity-50');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-white', 'ring-opacity-50');
            });
        });
    </script>
</body>
</html>