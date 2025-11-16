<div class="student-details-container">
    <!-- Header Section -->
    <div class="details-header">
        <div class="header-content">
            <div class="header-main">
                <div class="back-button">
                    <a href="{{ route('admin.student-boarders.index') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Boarders
                    </a>
                </div>
                <div class="student-title-section">
                    <div class="student-avatar-large">
                        <img src="{{ $studentBoarder->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($studentBoarder->user->name) . '&size=120' }}" 
                             alt="{{ $studentBoarder->user->name }}"
                             class="avatar-image">
                    </div>
                    <div class="student-info-main">
                        <h1 class="student-name">{{ $studentBoarder->user->name }}</h1>
                        <p class="student-email">{{ $studentBoarder->user->email }}</p>
                        <div class="status-badges">
                            <span class="status-badge {{ $studentBoarder->approval_status === 'approved' ? 'status-approved' : 'status-pending' }}">
                                <i class="fas {{ $studentBoarder->approval_status === 'approved' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                {{ ucfirst($studentBoarder->approval_status) }}
                            </span>
                            @if($studentBoarder->isPaymentOverdue())
                                <span class="status-badge status-overdue">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Payment Overdue
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="details-grid">
        <!-- Student Information Card -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-user-graduate card-icon"></i>
                <h3 class="card-title">Student Information</h3>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i>
                            Full Name
                        </div>
                        <div class="info-value">{{ $studentBoarder->user->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </div>
                        <div class="info-value">{{ $studentBoarder->user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-phone"></i>
                            Phone Number
                        </div>
                        <div class="info-value">{{ $studentBoarder->user->phone ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-badge"></i>
                            Student ID
                        </div>
                        <div class="info-value">{{ $studentBoarder->user->student_id ?? 'Not assigned' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boarding Details Card -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-home card-icon"></i>
                <h3 class="card-title">Boarding Details</h3>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-door-open"></i>
                            Room Assignment
                        </div>
                        <div class="info-value">
                            @if($studentBoarder->room)
                                <span class="room-code">{{ $studentBoarder->room->room_code }}</span>
                                <span class="room-type">({{ ucfirst($studentBoarder->room->type) }})</span>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-sign-in-alt"></i>
                            Check-in Date
                        </div>
                        <div class="info-value">
                            {{ $studentBoarder->booking->check_in_date?->format('F d, Y') ?? 'Not set' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-day"></i>
                            Monthly Due Date
                        </div>
                        <div class="info-value">
                            @if($studentBoarder->booking->monthly_due_date)
                                <span class="due-date">{{ $studentBoarder->booking->monthly_due_date }}th of each month</span>
                            @else
                                <span class="text-muted">Not configured</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-credit-card"></i>
                            Next Payment Due
                        </div>
                        <div class="info-value">
                            @if($studentBoarder->next_payment_due)
                                <span class="{{ $studentBoarder->isPaymentOverdue() ? 'text-overdue' : 'text-due' }}">
                                    {{ $studentBoarder->next_payment_due->format('F d, Y') }}
                                </span>
                                @if($studentBoarder->isPaymentOverdue())
                                    <i class="fas fa-exclamation-circle ml-1 text-overdue"></i>
                                @endif
                            @else
                                <span class="text-muted">Not scheduled</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($studentBoarder->room_assignment_notes)
                <div class="notes-section">
                    <div class="notes-label">
                        <i class="fas fa-sticky-note"></i>
                        Room Assignment Notes
                    </div>
                    <div class="notes-content">
                        {{ $studentBoarder->room_assignment_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Status Card -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-money-bill-wave card-icon"></i>
                <h3 class="card-title">Payment Status</h3>
            </div>
            <div class="card-content">
                <div class="payment-status-display">
                    <div class="payment-indicator {{ $studentBoarder->is_current_month_paid ? 'paid' : 'unpaid' }}">
                        <div class="payment-icon">
                            <i class="fas {{ $studentBoarder->is_current_month_paid ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        </div>
                        <div class="payment-info">
                            <div class="payment-status-text">
                                {{ $studentBoarder->is_current_month_paid ? 'Current Month Paid' : 'Current Month Unpaid' }}
                            </div>
                            <div class="payment-details">
                                @if($studentBoarder->is_current_month_paid)
                                    <span class="text-success">Payment received for current billing cycle</span>
                                @else
                                    <span class="text-warning">Awaiting payment for current month</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($studentBoarder->booking)
                <div class="payment-actions">
                    <a href="{{ route('admin.payments') }}?student={{ $studentBoarder->id }}" 
                       class="btn-action primary">
                        <i class="fas fa-receipt mr-2"></i>
                        View Payment History
                    </a>
                    <button class="btn-action secondary">
                        <i class="fas fa-edit mr-2"></i>
                        Update Payment Status
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Notices Card -->
        <div class="detail-card full-width">
            <div class="card-header">
                <i class="fas fa-bullhorn card-icon"></i>
                <h3 class="card-title">Directed Notices</h3>
                <a href="{{ route('admin.announcements.index', ['directed_to' => $studentBoarder->user_id]) }}" 
                   class="btn-action primary small">
                    <i class="fas fa-plus mr-1"></i>
                    Send New Notice
                </a>
            </div>
            <div class="card-content">
                @if($studentBoarder->directedAnnouncements->count() > 0)
                    <div class="notices-list">
                        @foreach($studentBoarder->directedAnnouncements->sortByDesc('created_at') as $announcement)
                            <div class="notice-item {{ $announcement->is_urgent ? 'urgent' : '' }}">
                                <div class="notice-header">
                                    <div class="notice-title">
                                        <i class="fas {{ $announcement->is_urgent ? 'fa-exclamation-triangle text-warning' : 'fa-info-circle' }} mr-2"></i>
                                        {{ $announcement->title }}
                                    </div>
                                    <div class="notice-meta">
                                        <span class="notice-date">
                                            {{ $announcement->created_at->format('M d, Y') }}
                                        </span>
                                        @if($announcement->is_urgent)
                                            <span class="urgent-badge">Urgent</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="notice-content">
                                    {{ $announcement->content }}
                                </div>
                                @if($announcement->attachment_path)
                                <div class="notice-attachment">
                                    <i class="fas fa-paperclip mr-2"></i>
                                    <a href="{{ Storage::url($announcement->attachment_path) }}" 
                                       target="_blank" 
                                       class="attachment-link">
                                        View Attachment
                                    </a>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-bullhorn empty-icon"></i>
                        <h4 class="empty-title">No Notices Yet</h4>
                        <p class="empty-description">
                            This student hasn't received any directed notices. 
                            Send them their first notice to get started.
                        </p>
                        <a href="{{ route('admin.announcements.index', ['directed_to' => $studentBoarder->user_id]) }}" 
                           class="btn-action primary">
                            <i class="fas fa-plus mr-2"></i>
                            Send First Notice
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="detail-card">
            <div class="card-header">
                <i class="fas fa-bolt card-icon"></i>
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-content">
                <div class="action-buttons-grid">
                    <a href="{{ route('admin.announcements.index', ['directed_to' => $studentBoarder->user_id]) }}" 
                       class="action-button warning">
                        <i class="fas fa-bullhorn"></i>
                        <span>Send Notice</span>
                    </a>
                    
                    @if($studentBoarder->approval_status === 'pending')
                    <button class="action-button success">
                        <i class="fas fa-check"></i>
                        <span>Approve</span>
                    </button>
                    @endif
                    
                    <a href="mailto:{{ $studentBoarder->user->email }}" 
                       class="action-button primary">
                        <i class="fas fa-envelope"></i>
                        <span>Send Email</span>
                    </a>
                    
                    <button class="action-button secondary">
                        <i class="fas fa-edit"></i>
                        <span>Edit Details</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>