{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>
    
    <!-- External Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
    
    <!-- Custom CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.styles.css') }}">

    <!-- Conditional CSS based on URI -->
    @if (str_contains(strtolower(request()->path()), 'student-boarders'))
        <link rel="stylesheet" href="{{ asset('css/student-details.css') }}">
        <link rel="stylesheet" href="{{ asset('css/student-boarders.css') }}">

    @endif

    @if (str_contains(strtolower(request()->path()), 'payments'))
        <link rel="stylesheet" href="{{ asset('css/admin-payments.css') }}">
    @endif
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50 dark:bg-zinc-900">

    <!-- Header -->
    <header class="admin-header">
        <div class="header-left">
            <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <h1>
                <i class="fas fa-home" aria-hidden="true"></i> 
                <span id="page-title">
                    @if(auth()->user()->role === 'admin')
                        Admin Portal
                    @else
                        Student Portal
                    @endif
                </span>
            </h1>
        </div>
        
        <!-- Profile Dropdown -->
        <div class="relative">
            <div class="profile" onclick="toggleDropdown()">
                <img
                    src="{{ auth()->user()->profile_photo_url }}"
                    alt="Profile"
                    class="w-8 h-8 rounded-full object-cover"
                >
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu" id="profileDropdown">
                <!-- User Info -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <img
                            src="{{ auth()->user()->profile_photo_url }}"
                            alt="Profile"
                            class="w-12 h-12 rounded-full object-cover"
                        >
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full 
                                        {{ auth()->user()->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                                {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Student' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Items -->
                <div class="p-2">
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        <i class="fas fa-user w-5 h-5 text-center"></i>
                        View Profile
                    </a>

                    <button onclick="toggleTheme()" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        <i class="fas fa-palette w-5 h-5 text-center"></i>
                        <span class="theme-text">Light Mode</span>
                    </button>

                    <a href="{{ route('settings') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        <i class="fas fa-cog w-5 h-5 text-center"></i>
                        Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt w-5 h-5 text-center"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1">
            @if(auth()->user()->role === 'admin')
                <!-- Admin Navigation -->
                <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="chart-bar">
                    Dashboard
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('admin.student-boarders.index') }}" :active="request()->routeIs('admin.student-boarders.index')" icon="academic-cap">
                    Students
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('admin.payments') }}" :active="request()->routeIs('admin.payments')" icon="credit-card">
                    Payments
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('admin.rooms') }}" :active="request()->routeIs('admin.rooms')" icon="home">
                    Rooms
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('admin.announcements.index') }}" :active="request()->routeIs('admin.announcements.index')" icon="bullhorn">
                    Announcements
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('admin.maintenance') }}" :active="request()->routeIs('admin.maintenance')" icon="wrench">
                    Maintenance
                </x-sidebar-link>
            @else
                <!-- Student Navigation -->
                <x-sidebar-link href="{{ route('student.dashboard') }}" :active="request()->routeIs('student.dashboard')" icon="home">
                    Dashboard
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('student.room') }}" :active="request()->routeIs('student.room')" icon="key">
                    My Room
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('student.payments') }}" :active="request()->routeIs('student.payments')" icon="credit-card">
                    Payments
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('student.maintenance') }}" :active="request()->routeIs('student.maintenance')" icon="wrench">
                    Maintenance Request
                </x-sidebar-link>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="p-6 lg:p-8">
            {{ $slot }}
        </div>
    </main>

    <script>
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Update aria label for accessibility
            const isCollapsed = sidebar.classList.contains('collapsed');
            sidebarToggle.setAttribute('aria-label', isCollapsed ? 'Open sidebar' : 'Close sidebar');
            sidebarToggle.querySelector('i').className = isCollapsed ? 'fas fa-bars' : 'fas fa-times';
        });

        // Dropdown functionality
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            const themeButton = event.currentTarget;
            const themeText = themeButton.querySelector('.theme-text');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeButton.innerHTML = `
                    <i class="fas fa-moon w-5 h-5 text-center"></i>
                    <span class="theme-text">Dark Mode</span>
                `;
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeButton.innerHTML = `
                    <i class="fas fa-sun w-5 h-5 text-center"></i>
                    <span class="theme-text">Light Mode</span>
                `;
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profile = document.querySelector('.profile');
            
            if (!profile.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                const themeButton = document.querySelector('button[onclick="toggleTheme()"]');
                if (themeButton) {
                    themeButton.innerHTML = `
                        <i class="fas fa-sun w-5 h-5 text-center"></i>
                        <span class="theme-text">Light Mode</span>
                    `;
                }
            }
        });

        // Livewire event listeners
        document.addEventListener('livewire:init', function() {
            // Listen for page reload events
            Livewire.on('page-reload', () => {
                setTimeout(() => {
                    window.location.reload();
                }, 100);
            });

            // Handle alert messages
            Livewire.on('alert', (data) => {
                alert(data.message);
            });
        });
    </script>
    
    @livewireScripts
</body>
</html>