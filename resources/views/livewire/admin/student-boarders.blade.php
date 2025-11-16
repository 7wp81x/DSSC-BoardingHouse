<div>
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Student Boarders</h2>
        </div>

        {{-- Tabs (Added Non Boarders) --}}
        <div class="mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button wire:click="$set('tab', 'non-boarders')" 
                        class="tab-link {{ $tab === 'non-boarders' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Non Boarders ({{ $nonBoardersCount }})
                </button>
                <button wire:click="$set('tab', 'pending')" 
                        class="tab-link {{ $tab === 'pending' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pending Approvals ({{ $pendingCount }})
                </button>
                <button wire:click="$set('tab', 'approved')" 
                        class="tab-link {{ $tab === 'approved' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Approved Boarders ({{ $approvedCount }})
                </button>
                <button wire:click="$set('tab', 'trashed')" 
                        class="tab-link {{ $tab === 'trashed' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Trashed ({{ $trashedCount }})
                </button>
            </nav>
        </div>

        {{-- Per-Tab Search Bar --}}
        <div class="mb-6 flex gap-4 items-center">
            <input type="text" 
                   placeholder="Search by name or email for {{ ucfirst(str_replace('-', ' ', $tab)) }}" 
                   class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                   wire:model.live="search">
            <button wire:click="search" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-600 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-700 transition-colors">Search</button>
            <button wire:click="resetSearch" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Clear</button>
        </div>

        {{-- Approve Modal with Date Picker for Next Payment --}}
        @if($showApproveModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Approve & Set Monthly Due Day</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Select any date—the <strong>day of the month</strong> will become the recurring due day (e.g., select Nov 5 to set due on the 5th of each month).</p>
                    
                    <input type="date" 
                           wire:model="selectedDueDate" 
                           id="due-date-picker" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md mb-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                           min="{{ now()->format('Y-m-d') }}" />
                    @error('selectedDueDate') 
                        <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> 
                    @enderror

                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeModal" class="px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Cancel</button>
                        <button wire:click="approve" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">Approve & Save</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Confirmation Modal --}}
        @if($showConfirmModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Confirm Action</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Are you sure you want to {{ $actionToConfirm === 'delete' ? 'move this student to trash' : 'permanently remove this student' }}?</p>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeConfirmModal" class="px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Cancel</button>
                        <button wire:click="performAction" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">Confirm</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full table-auto min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Room & Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Next Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($students as $item)
                    @if($tab === 'non-boarders')
                        @php $student = $item; @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $student->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=40' }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $student->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="text-gray-500 dark:text-gray-400">No room assigned</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="text-gray-500 dark:text-gray-400">N/A</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Non Boarder</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-500 dark:text-gray-400 text-xs">N/A</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('student.room') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">Request Boarding</a>
                            </td>
                        </tr>
                    @else
                        @php $student = $item; @endphp
                        <tr class="{{ $student->isPaymentOverdue() ? 'bg-red-50 dark:bg-red-950' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $student->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) . '&size=40' }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $student->user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div>Room: {{ $student->room->room_code ?? 'N/A' }} ({{ $student->room->type ?? 'N/A' }})</div>
                                <div>Check-in: {{ $student->booking->check_in_date?->format('M d, Y') ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Monthly Due: {{ $student->booking->monthly_due_date ?? 'Not Set' }}th</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div>{{ $student->next_payment_due?->format('M d, Y') ?? 'N/A' }}</div>
                                @if($student->isPaymentOverdue())
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300">Overdue</span>
                                @elseif($student->approval_status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Not Due Yet</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">Due Soon</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->trashed())
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Trashed</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $student->approval_status === 'approved' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300' }}">
                                        {{ ucfirst($student->approval_status) }}
                                    </span>
                                @endif
                                @if($student->trashed())
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Trashed on {{ $student->deleted_at?->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tab === 'approved' && $student->approval_status === 'approved')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $student->is_current_month_paid ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300' }}">
                                        {{ $student->is_current_month_paid ? 'Paid' : 'Unpaid' }}
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @if($tab !== 'trashed')
                                        <a href="{{ route('admin.student-boarders.show', $student) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">View</a>
                                        <a href="{{ route('admin.announcements.index', ['directed_to' => $student->user_id]) }}" class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors text-sm font-medium shadow-sm">Send Notice</a>
                                    @endif
                                    @if($student->approval_status === 'pending')
                                        <button wire:click="confirmApprove({{ $student->id }})" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">Approve</button>
                                    @elseif($tab === 'trashed')
                                        <button wire:click="restore({{ $student->id }})" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">Restore</button>
                                        <button wire:click="confirmAction('remove', {{ $student->id }})" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium shadow-sm">Remove</button>
                                    @else
                                        <button wire:click="confirmAction('delete', {{ $student->id }})" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium shadow-sm">Move to Trash</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $students->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            flatpickr('#due-date-picker', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                defaultDate: new Date().toISOString().split('T')[0],
                onChange: function(selectedDates, dateStr) {
                    @this.set('selectedDueDate', dateStr);
                }
            });
        });
    </script>
</div>