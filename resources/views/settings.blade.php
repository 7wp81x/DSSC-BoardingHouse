<x-app-layout>
    <x-slot name="title">Settings</x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Settings</h2>
                    
                    <div class="space-y-6">
                        <!-- Theme Settings -->
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Appearance</h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-700 dark:text-gray-300">Theme</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Choose between light and dark mode</p>
                                </div>
                                <button onclick="toggleTheme()" class="bg-gray-200 dark:bg-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    Toggle Theme
                                </button>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account</h3>
                            <div class="space-y-4">
                                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between w-full text-left bg-gray-50 dark:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 px-4 py-3 rounded-lg transition-colors group">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Edit Profile</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Update your personal information</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('profile.show') }}" class="flex items-center justify-between w-full text-left bg-gray-50 dark:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 px-4 py-3 rounded-lg transition-colors group">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">View Profile</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">See how your profile appears to others</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notifications</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-700 dark:text-gray-300">Email Notifications</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive updates via email</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-700 dark:text-gray-300">Push Notifications</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive browser notifications</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Privacy & Security</h3>
                            <div class="space-y-4">
                                <a href="#" class="flex items-center justify-between w-full text-left bg-gray-50 dark:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 px-4 py-3 rounded-lg transition-colors group">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Change Password</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Update your account password</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                
                                <a href="#" class="flex items-center justify-between w-full text-left bg-gray-50 dark:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 px-4 py-3 rounded-lg transition-colors group">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Two-Factor Authentication</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Add an extra layer of security</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="border border-red-200 dark:border-red-800 rounded-lg p-6 bg-red-50 dark:bg-red-900/20">
                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-4">Danger Zone</h3>
                            <div class="space-y-4">
                                <form method="POST" action="{{ route('profile.destroy') }}" class="flex items-center justify-between">
                                    @csrf
                                    @method('DELETE')
                                    <div>
                                        <p class="font-medium text-red-800 dark:text-red-300">Delete Account</p>
                                        <p class="text-sm text-red-600 dark:text-red-400">Permanently delete your account and all data</p>
                                    </div>
                                    <button type="button" onclick="confirmDelete()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                        Delete Account
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Back to Dashboard -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            const button = event.currentTarget;
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                button.textContent = 'Toggle Dark Mode';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                button.textContent = 'Toggle Light Mode';
            }
        }

        function confirmDelete() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                // You can add password confirmation here if needed
                event.target.closest('form').submit();
            }
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const button = document.querySelector('button[onclick="toggleTheme()"]');
            
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                if (button) button.textContent = 'Toggle Light Mode';
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
                if (button) button.textContent = 'Toggle Dark Mode';
            }
        });

        // Toggle switches functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('input[type="checkbox"]');
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    // Save toggle state to localStorage
                    const settingName = this.closest('div').querySelector('p').textContent.toLowerCase().replace(/\s+/g, '_');
                    localStorage.setItem(`setting_${settingName}`, this.checked);
                });

                // Load saved toggle states
                const settingName = toggle.closest('div').querySelector('p').textContent.toLowerCase().replace(/\s+/g, '_');
                const savedState = localStorage.getItem(`setting_${settingName}`);
                if (savedState !== null) {
                    toggle.checked = savedState === 'true';
                }
            });
        });
    </script>
</x-app-layout>