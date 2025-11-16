{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        :root {
            --bg-header: #ffffff;
            --header-height: 70px;
            --sidebar-width: 250px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --primary: #4f46e5;
            --accent: #6366f1;
            --light-text: #ffffff;
            --text-secondary: #6b7280;
        }

        .dark {
            --bg-header: #1f2937;
            --text-secondary: #9ca3af;
        }

        .admin-header {
            background-color: var(--bg-header);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: var(--header-height);
            z-index: 100;
            box-shadow: var(--shadow-sm);
            padding: 0 2rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary);
        }

        .dark .sidebar-toggle:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .header-left h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .header-left h1 i {
            color: var(--primary);
            margin-right: 0.5rem;
        }

        .profile {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .profile:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dark .profile:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            min-width: 250px;
            z-index: 1000;
            display: none;
        }

        .dark .dropdown-menu {
            background: #374151;
            border: 1px solid #4b5563;
        }

        .dropdown-menu.show {
            display: block;
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            border-right: 1px solid #e5e7eb;
            transition: transform 0.3s ease;
            z-index: 90;
            overflow-y: auto;
        }

        .dark .sidebar {
            background: #1f2937;
            border-right-color: #374151;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Navigation item styles */
        .nav-item {
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            padding: 1rem 1.5rem;
            text-decoration: none;
            transition: 0.3s;
            gap: 0.8rem;
            border-left: 4px solid transparent;
        }

        .nav-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dark .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-item.active {
            background-color: var(--primary);
            color: var(--light-text);
            border-left: 4px solid var(--accent);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 dark:bg-zinc-900">

    <!-- Header -->
    <header class="admin-header">
        <div class="header-left">
            <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <h1><i class="fas fa-home" aria-hidden="true"></i> <span id="page-title">
                @if(auth()->user()->role === 'admin')
                    Admin Portal
                @else
                    Student Portal
                @endif
            </span></h1>
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
                        Light Mode
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
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeButton.innerHTML = `
                    <i class="fas fa-moon w-5 h-5 text-center"></i>
                    Dark Mode
                `;
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeButton.innerHTML = `
                    <i class="fas fa-sun w-5 h-5 text-center"></i>
                    Light Mode
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
                        Light Mode
                    `;
                }
            }
        });

        // Update page title based on current page
        
    </script>
@livewireScripts
</body>
</html>