<div class="max-w-4xl mx-auto space-y-8">
    <h1 class="text-4xl font-bold">My Payments</h1>

    @if (!$this->booking)
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 rounded-2xl p-8 text-center">
            <i class="fas fa-bed text-6xl text-yellow-600 mb-4"></i>
            <h2 class="text-2xl font-bold text-yellow-800 dark:text-yellow-300">No Room Booked Yet</h2>
            <p class="mt-4 text-lg">You haven't booked a room. Visit the <a href="{{ route('student.room') }}" class="underline font-semibold">Rooms</a> page to book one!</p>
        </div>
    @elseif($this->booking?->bills->where('status', 'pending')->count())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-red-800 dark:text-red-300">Pending Bill</h2>
            <div class="mt-4 grid grid-cols-2 gap-8">
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Amount Due</p>
                    <p class="text-4xl font-bold">₱{{ number_format($this->booking->bills->where('status','pending')->first()->amount) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Due Date</p>
                    <p class="text-3xl font-bold">
                        {{ $this->booking->bills->where('status','pending')->first()->due_date->format('F j, Y') }}
                    </p>
                </div>
            </div>

            <div class="mt-8">
                <livewire:student.upload-receipt :booking="$this->booking" />
            </div>
        </div>
    @else
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-2xl p-8 text-center">
            <i class="fas fa-check-circle text-8xl text-green-600 mb-4"></i>
            <h2 class="text-3xl font-bold text-green-800 dark:text-green-300">All Paid!</h2>
            <p class="text-xl mt-4">You're all caught up. Great job!</p>
        </div>
    @endif

    @if($this->booking)
        <div class="mt-12">
            <h3 class="text-2xl font-bold mb-6">Payment History</h3>

            @forelse($this->booking->bills as $bill)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg font-semibold">₱{{ number_format($bill->amount) }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Due: {{ $bill->due_date?->format('M d, Y') ?? 'No due date' }}
                            </p>
                        </div>
                        <span class="px-4 py-2 rounded-full text-sm font-bold
                            {{ $bill->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 
                               ($bill->status === 'overdue' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 
                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100') }}">
                            {{ ucfirst($bill->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">No bills generated yet.</p>
            @endforelse
        </div>
    @endif
</div>