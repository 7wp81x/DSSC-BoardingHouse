<div class="student-boarders-container">
    @if (session()->has('message'))
        <div class="alert-success">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('message') }}
                </div>
                <button type="button" class="alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="main-card">
        <!-- Header Section -->
        <div class="card-header">
            <div class="header-content">
                <div class="header-title-section">
                    <i class="fas fa-users header-icon"></i>
                    <div>
                        <h2 class="header-title">Student Boarders Management</h2>
                        <p class="header-subtitle">Manage student boarding applications and approvals</p>
                    </div>
                </div>
                <div class="header-stats">
                    <div class="stat-badge total">
                        <span class="stat-number">{{ $nonBoardersCount + $pendingCount + $approvedCount }}</span>
                        <span class="stat-label">Total Students</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs-container">
            <nav class="tabs-nav">
                <button wire:click="$set('tab', 'non-boarders')" 
                        class="tab-button {{ $tab === 'non-boarders' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-user-clock tab-icon"></i>
                    <span>Non Boarders</span>
                    <span class="tab-count">{{ $nonBoardersCount }}</span>
                </button>
                <button wire:click="$set('tab', 'pending')" 
                        class="tab-button {{ $tab === 'pending' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-clock tab-icon"></i>
                    <span>Pending</span>
                    <span class="tab-count">{{ $pendingCount }}</span>
                </button>
                <button wire:click="$set('tab', 'approved')" 
                        class="tab-button {{ $tab === 'approved' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-check-circle tab-icon"></i>
                    <span>Approved</span>
                    <span class="tab-count">{{ $approvedCount }}</span>
                </button>
                <button wire:click="$set('tab', 'trashed')" 
                        class="tab-button {{ $tab === 'trashed' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-trash tab-icon"></i>
                    <span>Trashed</span>
                    <span class="tab-count">{{ $trashedCount }}</span>
                </button>
            </nav>
        </div>

        <!-- Search and Filters -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           placeholder="Search {{ ucfirst(str_replace('-', ' ', $tab)) }} by name or email..." 
                           class="search-input" 
                           wire:model.live="search">
                    @if($search)
                        <button wire:click="resetSearch" class="clear-search">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
                <button wire:click="search" class="search-button">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
            </div>
        </div>

        <!-- Approve Modal -->
        @if($showApproveModal)
            <div class="modal-overlay">
                <div class="modal-container">
                    <div class="modal-header">
                        <i class="fas fa-calendar-check modal-icon"></i>
                        <h3 class="modal-title">Approve & Set Monthly Due Day</h3>
                    </div>
                    <div class="modal-content">
                        <p class="modal-description">
                            Select any date—the <strong>day of the month</strong> will become the recurring due day 
                            (e.g., select Nov 5 to set due on the 5th of each month).
                        </p>
                        
                        <div class="date-picker-container">
                            <label class="date-picker-label">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Select Due Date
                            </label>
                            <input type="date" 
                                   wire:model="selectedDueDate" 
                                   id="due-date-picker" 
                                   class="date-picker" 
                                   min="{{ now()->format('Y-m-d') }}" />
                            @error('selectedDueDate') 
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </span> 
                            @enderror
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button wire:click="closeModal" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button wire:click="approve" class="btn-primary">
                            <i class="fas fa-check mr-2"></i>
                            Approve & Save
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Confirmation Modal -->
        @if($showConfirmModal)
            <div class="modal-overlay">
                <div class="modal-container">
                    <div class="modal-header">
                        <i class="fas fa-exclamation-triangle modal-icon warning"></i>
                        <h3 class="modal-title">Confirm Action</h3>
                    </div>
                    <div class="modal-content">
                        <p class="modal-description">
                            Are you sure you want to 
                            <strong>{{ $actionToConfirm === 'delete' ? 'move this student to trash' : 'permanently remove this student' }}</strong>?
                            This action {{ $actionToConfirm === 'remove' ? 'cannot be undone' : 'can be reversed from the trash' }}.
                        </p>
                    </div>
                    <div class="modal-actions">
                        <button wire:click="closeConfirmModal" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button wire:click="performAction" class="btn-danger">
                            <i class="fas fa-check mr-2"></i>
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Students Table -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="students-table">
                    <thead class="table-header">
                        <tr>
                            <th class="table-th student-col">Student Information</th>
                            <th class="table-th room-col">Room & Schedule</th>
                            <th class="table-th payment-col">Payment Status</th>
                            <th class="table-th status-col">Status</th>
                            <th class="table-th actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                    @forelse ($students as $item)
                        @if($tab === 'non-boarders')
                            @php $student = $item; @endphp
                            <tr class="table-row">
                                <td class="table-td">
                                    <div class="student-info">
                                        <img class="student-avatar" src="{{ $student->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=40' }}" alt="{{ $student->name }}">
                                        <div class="student-details">
                                            <div class="student-name">{{ $student->name }}</div>
                                            <div class="student-email">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <div class="room-info">
                                        <div class="no-room">No room assigned</div>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <span class="status-na">N/A</span>
                                </td>
                                <td class="table-td">
                                    <span class="status-badge non-boarder">
                                        <i class="fas fa-user-clock mr-1"></i>
                                        Non Boarder
                                    </span>
                                </td>
                                <td class="table-td">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.student-boarders.show', $student) }}" class="btn-action primary">
                                            <i class="fas fa-eye"></i>
                                            View Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @php $student = $item; @endphp
                            <tr class="table-row {{ $student->isPaymentOverdue() ? 'overdue-row' : '' }}">
                                <td class="table-td">
                                    <div class="student-info">
                                        <img class="student-avatar" src="{{ $student->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) . '&size=40' }}" alt="{{ $student->user->name }}">
                                        <div class="student-details">
                                            <div class="student-name">{{ $student->user->name }}</div>
                                            <div class="student-email">{{ $student->user->email }}</div>
                                            @if($student->trashed())
                                                <div class="trashed-date">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Trashed on {{ $student->deleted_at?->format('M d, Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <div class="room-info">
                                        <div class="room-code">
                                            <i class="fas fa-door-open mr-2"></i>
                                            {{ $student->room->room_code ?? 'N/A' }} ({{ $student->room->type ?? 'N/A' }})
                                        </div>
                                        <div class="room-dates">
                                            <div class="date-item">
                                                <i class="fas fa-sign-in-alt mr-2"></i>
                                                {{ $student->booking->check_in_date?->format('M d, Y') ?? 'N/A' }}
                                            </div>
                                            <div class="date-item">
                                                <i class="fas fa-calendar-day mr-2"></i>
                                                Due on {{ $student->booking->monthly_due_date ?? 'Not Set' }}th
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <div class="payment-info">
                                        <div class="next-payment">
                                            {{ $student->next_payment_due?->format('M d, Y') ?? 'N/A' }}
                                        </div>
                                        @if($student->isPaymentOverdue())
                                            <span class="status-badge overdue">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Overdue
                                            </span>
                                        @elseif($student->approval_status === 'pending')
                                            <span class="status-badge pending">
                                                <i class="fas fa-clock mr-1"></i>
                                                Not Due Yet
                                            </span>
                                        @else
                                            <span class="status-badge due-soon">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Due Soon
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="table-td">
                                    @if($student->trashed())
                                        <span class="status-badge trashed">
                                            <i class="fas fa-trash mr-1"></i>
                                            Trashed
                                        </span>
                                    @else
                                        <span class="status-badge {{ $student->approval_status === 'approved' ? 'approved' : 'pending' }}">
                                            <i class="fas {{ $student->approval_status === 'approved' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                            {{ ucfirst($student->approval_status) }}
                                        </span>
                                    @endif
                                    @if($tab === 'approved' && $student->approval_status === 'approved')
                                        <div class="payment-status">
                                            <span class="status-badge {{ $student->is_current_month_paid ? 'paid' : 'unpaid' }} small">
                                                {{ $student->is_current_month_paid ? 'Paid' : 'Unpaid' }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="table-td">
                                    <div class="action-buttons">
                                        @if($tab !== 'trashed')
                                            <a href="{{ route('admin.student-boarders.show', $student) }}" class="btn-action primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.announcements.index', ['directed_to' => $student->user_id]) }}" class="btn-action warning" title="Send Notice">
                                                <i class="fas fa-bullhorn"></i>
                                            </a>
                                        @endif
                                        @if($student->approval_status === 'pending')
                                            <button wire:click="confirmApprove({{ $student->id }})" class="btn-action success" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @elseif($tab === 'trashed')
                                            <button wire:click="restore({{ $student->id }})" class="btn-action success" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button wire:click="confirmAction('remove', {{ $student->id }})" class="btn-action danger" title="Permanently Remove">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @else
                                            <button wire:click="confirmAction('delete', {{ $student->id }})" class="btn-action danger" title="Move to Trash">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr class="table-row">
                            <td colspan="5" class="table-td empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-users empty-icon"></i>
                                    <h3 class="empty-title">No Students Found</h3>
                                    <p class="empty-description">
                                        @if($search)
                                            No students match your search criteria. Try adjusting your search terms.
                                        @else
                                            There are no students in this category at the moment.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="pagination-container">
                {{ $students->links() }}
            </div>
        @endif
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