<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($announcements as $announcement)
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow hover:shadow-lg transition">
            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100">{{ $announcement->title }}</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">{{ Str::limit($announcement->content, 120) }}</p>
            @if($announcement->published_at)
                <p class="text-sm text-gray-400 mt-2">{{ $announcement->published_at->format('M d, Y') }}</p>
            @endif
        </div>
    @empty
        <p class="text-gray-500 dark:text-gray-400 col-span-2">No announcements available.</p>
    @endforelse
</div>
