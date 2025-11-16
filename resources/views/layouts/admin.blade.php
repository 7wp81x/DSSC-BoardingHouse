{{-- Similarly updated for admin: resources/views/layouts/admin.blade.php - Header with profile dropdown, sidebar below, adjusted sizes/styles --}}
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 dark:bg-zinc-900 antialiased">

    <!-- Fixed Header -->
    <header class="fixed top-0 left-0 right-0 h-20 bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700 z-50 flex items-center justify-between px-8 shadow-sm">
        <!-- Logo on Left -->
        <a href="{{ route('admin.dashboard') }}">
            <x-app-logo class="h-9" />
        </a>

        <!-- Profile Dropdown on Right -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-3 cursor-pointer p-2 rounded-lg transition-colors duration-300 hover:bg-gray-100 dark:hover:bg-zinc-700/50">
                <img
                    src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff&bold=true' }}"
                    alt="Profile"
                    class="w-8 h-8 rounded-full ring-2 ring-white dark:ring-zinc-700"
                >
                <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-64 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-gray-200 dark:border-zinc-700 py-2 z-50">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                    <div class="flex items-center gap-3">
                        <img
                            src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff&bold=true' }}"
                            alt="Profile"
                            class="w-10 h-10 rounded-full"
                        >
                        <div>
                            <p class="font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Admin</p>
                        </div>
                    </div>
                </div>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700/50">View Profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700/50">Light Mode</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700/50">Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sidebar (starts below header) -->
    <aside id="sidebar" class="fixed top-20 left-0 bottom-0 z-40 w-[250px] bg-white dark:bg-zinc-800 border-r border-gray-200 dark:border-zinc-700 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 overflow-y-auto" aria-label="Sidebar">
        <!-- Navigation (no logo div, removed p-6 border-b) -->
        <nav class="flex-1 px-4 py-6 space-y-1.5">
            <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="home">
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('admin.payments') }}" :active="request()->routeIs('admin.payments')" icon="credit-card">
                Payments
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('admin.rooms') }}" :active="request()->routeIs('admin.rooms')" icon="building-office">
                Rooms
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('admin.announcements') }}" :active="request()->routeIs('admin.announcements')" icon="megaphone">
                Announcements
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('admin.maintenance') }}" :active="request()->routeIs('admin.maintenance')" icon="wrench-screwdriver">
                Maintenance
            </x-sidebar-link>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen pt-20 lg:ml-[250px]">
        <!-- Mobile Toggle Button (adjusted position) -->
        <button id="sidebar-toggle" class="lg:hidden fixed top-24 left-4 z-50 p-2 bg-purple-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <main class="flex-1 p-6 lg:p-10">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    <!-- Alpine.js for sidebar toggle and dropdown -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false,
                toggle() {
                    this.open = !this.open;
                    document.getElementById('sidebar').classList.toggle('-translate-x-full', !this.open);
                }
            });
        });

        document.getElementById('sidebar-toggle').addEventListener('click', () => {
            Alpine.store('sidebar').toggle();
        });
    </script>
</body>
</html>