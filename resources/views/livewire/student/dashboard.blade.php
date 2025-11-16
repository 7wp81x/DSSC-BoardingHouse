<div class="space-y-8">

<div>
    <h2 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400 mb-8">
        Welcome back, {{ auth()->user()->name }}!
    </h2>

    @if($booking)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition">
                <h3 class="text-lg opacity-90">Your Room</h3>
                <div class="text-4xl font-bold mt-3">{{ $booking->room->room_code }}</div>
                <div class="opacity-80">{{ ucfirst($booking->room->type) }} Room</div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition">
                <h3 class="text-lg opacity-90">Monthly Rent</h3>
                <div class="text-4xl font-bold mt-3">₱{{ number_format($booking->room->price) }}</div>
            </div>
            <div class="bg-gradient-to-br from-pink-500 to-rose-600 text-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition">
                <h3 class="text-lg opacity-90">Next Due</h3>
                <div class="text-4xl font-bold mt-3">
                    {{ $nextBill?->due_date->format('M d') ?? 'Paid' }}
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-500 text-white text-center p-12 rounded-2xl shadow-xl text-2xl font-bold">
            You are not assigned to any room yet.
        </div>
    @endif

    <div class="mt-12">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Latest Announcements</h3>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($announcements as $a)
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow hover:shadow-xl transition border border-gray-100 dark:border-zinc-700">
                    <h4 class="font-bold text-indigo-600 dark:text-indigo-400">{{ $a->title }}</h4>
                    <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm">{{ Str::limit($a->content, 100) }}</p>
                    <p class="text-xs text-gray-500 mt-4">{{ $a->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-gray-500 col-span-full text-center py-8">No announcements yet.</p>
            @endforelse
        </div>
    </div>
</div>
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>