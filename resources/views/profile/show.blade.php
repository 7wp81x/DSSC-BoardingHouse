<x-app-layout>
    <x-slot name="title">Profile - {{ auth()->user()->name }}</x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Profile Information</h2>
                    
                    <div class="space-y-6">
                        <!-- Profile Header -->
                        <div class="flex items-center gap-4">
                            <img
                                src="{{ auth()->user()->profile_photo_url }}"
                                alt="Profile"
                                class="w-20 h-20 rounded-full object-cover"
                            >
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                <div class="flex gap-2 mt-1">
                                    <span class="inline-block px-3 py-1 text-sm font-medium rounded-full 
                                                {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                                        {{ auth()->user()->formatted_role }}
                                    </span>
                                    @if(auth()->user()->student_id)
                                    <span class="inline-block px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full">
                                        ID: {{ auth()->user()->student_id }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Profile Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <p class="text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <p class="text-gray-900 dark:text-white">{{ auth()->user()->email }}</p>
                            </div>

                            @if(auth()->user()->student_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student ID</label>
                                <p class="text-gray-900 dark:text-white">{{ auth()->user()->student_id }}</p>
                            </div>
                            @endif

                            @if(auth()->user()->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                <p class="text-gray-900 dark:text-white">{{ auth()->user()->phone }}</p>
                            </div>
                            @endif

                            @if(auth()->user()->emergency_contact)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Emergency Contact</label>
                                <p class="text-gray-900 dark:text-white">{{ auth()->user()->emergency_contact }}</p>
                            </div>
                            @endif

                            @if(auth()->user()->emergency_phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Emergency Phone</label>
                                <p class="text-gray-900 dark:text-white">{{ auth()->user()->emergency_phone }}</p>
                            </div>
                            @endif

                            @if(auth()->user()->address)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                                <p class="text-gray-900 dark:text-white">{{ auth()->user()->address }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('profile.edit') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                Edit Profile
                            </a>
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>