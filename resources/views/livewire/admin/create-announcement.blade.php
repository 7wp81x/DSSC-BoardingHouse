<div>
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-white rounded-md">
            {{ session('message') }}
        </div>
    @endif
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 max-w-2xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Create Announcement</h2>

        <form wire:submit="save">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Title</label>
                <input type="text" wire:model="title" id="title" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                @error('title') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Content</label>
                <textarea wire:model="content" id="content" rows="6" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                @error('content') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="is_pinned" class="rounded border-gray-300 dark:border-gray-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-white">Pin to top</span>
                </label>
            </div>

            <div class="mb-4">
                <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Publish Date (optional)</label>
                <input type="date" wire:model="published_at" id="published_at" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                @error('published_at') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            @if($directed_to_user_id)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Direct to Student</label>

                    <div class="w-full px-3 py-2 border rounded-md bg-gray-200 dark:bg-gray-600
                                text-gray-700 dark:text-gray-300 opacity-80 cursor-not-allowed">
                        <p class="text-sm"> {{ \App\Models\User::find($directed_to_user_id)->name ?? 'Student ID ' . $directed_to_user_id }}
                        </p>
                    </div>
                </div>
            @else

                <div class="mb-4">
                    <label for="directed_to_user_id" class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Direct to Student (optional)</label>
                    <select wire:model="directed_to_user_id" id="directed_to_user_id" class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">General (all students)</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                    @error('directed_to_user_id') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="bg-[#1f2937] hover:bg-[#111827] text-white py-2 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2">Create & Send</button>
            </div>
        </form>
    </div>
</div>