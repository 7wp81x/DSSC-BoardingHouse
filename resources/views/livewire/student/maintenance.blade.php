<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex justify-between items-center">
        <h1 class="text-4xl font-bold">Maintenance Requests</h1>
        <a href="{{ route('student.maintenance') }}" wire:navigate>
            <flux-button icon="plus" variant="primary">New Request</flux-button>
        </a>
    </div>

    @forelse($requests as $request)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold">{{ $request->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Room {{ $request->room->room_code ?? 'Unknown' }} • 
                        Submitted {{ $request->created_at->diffForHumans() }}
                    </p>
                    <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $request->description }}</p>
                </div>

                <div class="text-right">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-bold
                        @switch($request->status)
                            @case('open') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                            @case('in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                            @case('resolved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                            @case('closed') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @break
                        @endswitch">
                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                    </span>

                    @if($request->priority === 'urgent')
                        <span class="block mt-2 text-xs font-bold text-red-600">URGENT</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-12 text-center border-2 border-dashed border-gray-300 dark:border-gray-600">
            <i class="fas fa-wrench text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-700 dark:text-gray-300">No Maintenance Requests</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Everything is working perfectly!</p>
        </div>
    @endforelse
</div>