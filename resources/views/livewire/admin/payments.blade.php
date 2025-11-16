<div class="payments-admin-container">
    <!-- Flash Messages -->
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

    @if (session()->has('error'))
        <div class="alert-error">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
                <button type="button" class="alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Card -->
    <div class="main-card">
        <!-- Header Section -->
        <div class="card-header">
            <div class="header-content">
                <div class="header-title-section">
                    <i class="fas fa-credit-card header-icon"></i>
                    <div>
                        <h1 class="header-title">Payment Management</h1>
                        <p class="header-subtitle">Review and manage student payments</p>
                    </div>
                </div>
                <div class="header-stats">
                    <div class="stat-badge pending">
                        <span class="stat-number">{{ $statusCounts['pending'] }}</span>
                        <span class="stat-label">Pending</span>
                    </div>
                    <div class="stat-badge approved">
                        <span class="stat-number">{{ $statusCounts['approved'] }}</span>
                        <span class="stat-label">Approved</span>
                    </div>
                    <div class="stat-badge total">
                        <span class="stat-number">{{ array_sum($statusCounts) }}</span>
                        <span class="stat-label">Total</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs-container">
            <nav class="tabs-nav">
                <button wire:click="switchTab('pending')" 
                        class="tab-button {{ $currentTab === 'pending' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-clock tab-icon"></i>
                    <span>Pending Payments</span>
                    @if($statusCounts['pending'] > 0)
                        <span class="tab-count badge-pending">{{ $statusCounts['pending'] }}</span>
                    @endif
                </button>
                <button wire:click="switchTab('history')" 
                        class="tab-button {{ $currentTab === 'history' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-history tab-icon"></i>
                    <span>Payment History</span>
                </button>
                <button wire:click="switchTab('methods')" 
                        class="tab-button {{ $currentTab === 'methods' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-cog tab-icon"></i>
                    <span>Payment Methods</span>
                </button>
            </nav>
        </div>

        <!-- Pending Payments & History Tabs Content -->
        @if(in_array($currentTab, ['pending', 'history']))
        <!-- Search and Filters -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           placeholder="Search by reference number, student name or email..." 
                           class="search-input" 
                           wire:model.live="search">
                    @if($search)
                        <button wire:click="$set('search', '')" class="clear-search">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
                
                <div class="filters-grid">
                    @if($currentTab === 'history')
                    <select wire:model.live="statusFilter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @endif
                    
                    <select wire:model.live="methodFilter" class="filter-select">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->name }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                    
                    <select wire:model.live="studentFilter" class="filter-select">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                    
                    <div class="date-filters">
                        <input type="date" wire:model.live="dateFrom" class="date-input" placeholder="From Date">
                        <span class="date-separator">to</span>
                        <input type="date" wire:model.live="dateTo" class="date-input" placeholder="To Date">
                    </div>
                    
                    <button wire:click="resetFilters"  style="margin-left: 80px;height: 40px;" class="btn-secondary gap-4 flex items-center gap-2 flex-wrap">
                        <i class="fas fa-refresh mr-2"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="payments-table">
                    <thead class="table-header">
                        <tr>
                            <th class="table-th student-col">Student</th>
                            <th class="table-th amount-col">Amount</th>
                            <th class="table-th method-col">Method</th>
                            <th class="table-th reference-col">Reference</th>
                            <th class="table-th date-col">Date</th>
                            <th class="table-th status-col">Status</th>
                            <th class="table-th actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                    @forelse ($payments as $payment)
                        <tr class="table-row status-{{ $payment->status }}">
                            <td class="table-td">
                                <div class="student-info">
                                    <img class="student-avatar" 
                                         src="{{ $payment->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->user->name) . '&size=40' }}" 
                                         alt="{{ $payment->user->name }}">
                                    <div class="student-details">
                                        <div class="student-name">{{ $payment->user->name }}</div>
                                        <div class="student-email">{{ $payment->user->email }}</div>
                                        @if($payment->bill && $payment->bill->room)
                                            <div class="student-room">Room {{ $payment->bill->room->room_code }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="table-td">
                                <div class="amount-display">
                                    <span class="amount-value">₱{{ number_format($payment->amount, 2) }}</span>
                                    @if($payment->bill)
                                        <div class="bill-reference">Bill #{{ $payment->bill->id }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="table-td">
                                <span class="payment-method">
                                    <i class="fas {{ $payment->payment_method === 'GCash' ? 'fa-mobile-alt' : ($payment->payment_method === 'PayPal' ? 'fa-paypal' : 'fa-university') }} mr-2"></i>
                                    {{ $payment->payment_method }}
                                </span>
                            </td>
                            <td class="table-td">
                                <div class="reference-info">
                                    <code class="reference-number">{{ $payment->reference_number ?? 'N/A' }}</code>
                                    @if($payment->receipt_image)
                                        <button wire:click="viewPayment({{ $payment->id }})" 
                                                class="receipt-link"
                                                title="View Receipt">
                                            <i class="fas fa-receipt mr-1"></i>
                                            Receipt
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="table-td">
                                <div class="date-info">
                                    <div class="date-main">{{ $payment->created_at->format('M d, Y') }}</div>
                                    <div class="date-time">{{ $payment->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td class="table-td">
                                <span class="status-badge status-{{ $payment->status }}">
                                    <i class="fas fa-{{ $payment->getStatusIcon() }} mr-1"></i>
                                    {{ ucfirst($payment->status) }}
                                </span>
                                @if($payment->processed_at)
                                    <div class="processed-info">
                                        by {{ $payment->processor->name ?? 'Admin' }}
                                    </div>
                                @endif
                            </td>
                            <td class="table-td">
                                <div class="action-buttons">
                                    <button wire:click="viewPayment({{ $payment->id }})" 
                                            class="btn-action primary"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if($payment->isPending())
                                        <button wire:click="openApproveModal({{ $payment->id }})" 
                                                class="btn-action success"
                                                title="Approve Payment">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button wire:click="openRejectModal({{ $payment->id }})" 
                                                class="btn-action warning"
                                                title="Reject Payment">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button wire:click="cancelPayment({{ $payment->id }})" 
                                                class="btn-action danger"
                                                title="Cancel Payment"
                                                onclick="return confirm('Are you sure you want to cancel this payment?')">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @elseif($payment->isRejected() && $payment->rejection_reason)
                                        <button wire:click="viewPayment({{ $payment->id }})" 
                                                class="btn-action warning"
                                                title="View Rejection Reason">
                                            <i class="fas fa-comment-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="table-row">
                            <td colspan="7" class="table-td empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-credit-card empty-icon"></i>
                                    <h3 class="empty-title">No Payments Found</h3>
                                    <p class="empty-description">
                                        @if($search || $statusFilter || $methodFilter || $studentFilter || $dateFrom || $dateTo)
                                            No payments match your current filters. Try adjusting your search criteria.
                                        @else
                                            There are no {{ $currentTab === 'pending' ? 'pending' : 'historical' }} payments at the moment.
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
        @if($payments->hasPages())
            <div class="pagination-container">
                {{ $payments->links() }}
            </div>
        @endif
        @endif

        <!-- Payment Methods Tab Content -->
        @if($currentTab === 'methods')
        <div class="methods-section">
            <div class="methods-header">
                <h3 class="methods-title">Configure Payment Methods</h3>
                <p class="methods-description">
                    Manage available payment methods for students. Enable/disable methods and configure their details.
                </p>
                <button wire:click="showMethodForm()" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Method
                </button>
            </div>

            <div class="methods-grid">
                @foreach($paymentMethods as $method)
                    <div class="method-card {{ $method->is_active ? 'active' : 'inactive' }}">
                        <div class="method-header">
                            <div class="method-info">
                                <h4 class="method-name">{{ $method->name }}</h4>
                                <span class="method-type">{{ ucfirst(str_replace('_', ' ', $method->type)) }}</span>
                            </div>
                            <div class="method-actions">
                                <button wire:click="toggleMethodStatus({{ $method->id }})" 
                                        class="toggle-btn {{ $method->is_active ? 'active' : 'inactive' }}"
                                        title="{{ $method->is_active ? 'Disable' : 'Enable' }} Method">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button wire:click="showMethodForm({{ $method->id }})" 
                                        class="btn-action primary"
                                        title="Edit Method">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="deleteMethod({{ $method->id }})" 
                                        class="btn-action danger"
                                        title="Delete Method"
                                        onclick="return confirm('Are you sure you want to delete this payment method?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="method-content">
                            @if($method->instructions)
                                <div class="method-instructions">
                                    <strong>Instructions:</strong> {{ $method->instructions }}
                                </div>
                            @endif
                            
                            <div class="method-details">
                                @if($method->account_name)
                                    <div class="detail-item">
                                        <span class="detail-label">Account Name:</span>
                                        <span class="detail-value">{{ $method->account_name }}</span>
                                    </div>
                                @endif
                                
                                @if($method->account_number)
                                    <div class="detail-item">
                                        <span class="detail-label">Account Number:</span>
                                        <span class="detail-value">{{ $method->account_number }}</span>
                                    </div>
                                @endif
                                
                                @if($method->email)
                                    <div class="detail-item">
                                        <span class="detail-label">Email:</span>
                                        <span class="detail-value">{{ $method->email }}</span>
                                    </div>
                                @endif
                                
                                @if($method->qr_code_image)
                                    <div class="detail-item">
                                        <span class="detail-label">QR Code:</span>
                                        <div class="qr-code-preview">
                                            <img src="{{ Storage::url($method->qr_code_image) }}" 
                                                 alt="{{ $method->name }} QR Code"
                                                 class="qr-image">
                                            <a href="{{ Storage::url($method->qr_code_image) }}" 
                                               target="_blank" 
                                               class="view-qr-link">
                                                View Full Size
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="method-footer">
                            <span class="method-stats">
                                {{ $method->payments()->count() }} payments
                            </span>
                            <span class="method-order">
                                Order: {{ $method->sort_order }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- View Payment Modal -->
    @if($showViewModal && $selectedPayment)
        <div class="modal-overlay">
            <div class="modal-container large">
                <div class="modal-header">
                    <i class="fas fa-receipt modal-icon"></i>
                    <h3 class="modal-title">Payment Details</h3>
                    <button wire:click="closeModals" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-content">
                    <div class="payment-details-grid">
                        <div class="detail-section">
                            <h4 class="section-title">Student Information</h4>
                            <div class="detail-item">
                                <span class="detail-label">Name:</span>
                                <span class="detail-value">{{ $selectedPayment->user->name }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">{{ $selectedPayment->user->email }}</span>
                            </div>
                            @if($selectedPayment->bill && $selectedPayment->bill->room)
                            <div class="detail-item">
                                <span class="detail-label">Room:</span>
                                <span class="detail-value">
                                    {{ $selectedPayment->bill->room->room_code }} ({{ $selectedPayment->bill->room->type }})
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="detail-section">
                            <h4 class="section-title">Payment Information</h4>
                            <div class="detail-item">
                                <span class="detail-label">Amount:</span>
                                <span class="detail-value">₱{{ number_format($selectedPayment->amount, 2) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Method:</span>
                                <span class="detail-value">{{ $selectedPayment->payment_method }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Reference:</span>
                                <span class="detail-value">{{ $selectedPayment->reference_number ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge status-{{ $selectedPayment->status }}">
                                        {{ ucfirst($selectedPayment->status) }}
                                    </span>
                                </span>
                            </div>
                        </div>

                        @if($selectedPayment->receipt_image)
                        <div class="detail-section full-width">
                            <h4 class="section-title">Receipt Image</h4>
                            <div class="receipt-preview">
                                <img src="{{ Storage::url($selectedPayment->receipt_image) }}" 
                                     alt="Payment Receipt"
                                     class="receipt-image">
                                <a href="{{ Storage::url($selectedPayment->receipt_image) }}" 
                                   target="_blank" 
                                   class="btn-action primary">
                                    <i class="fas fa-expand mr-2"></i>
                                    View Full Size
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($selectedPayment->rejection_reason)
                        <div class="detail-section full-width">
                            <h4 class="section-title">Rejection Reason</h4>
                            <div class="rejection-reason">
                                <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
                                {{ $selectedPayment->rejection_reason }}
                            </div>
                        </div>
                        @endif

                        <div class="detail-section">
                            <h4 class="section-title">Timeline</h4>
                            <div class="detail-item">
                                <span class="detail-label">Submitted:</span>
                                <span class="detail-value">{{ $selectedPayment->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            @if($selectedPayment->processed_at)
                            <div class="detail-item">
                                <span class="detail-label">Processed:</span>
                                <span class="detail-value">{{ $selectedPayment->processed_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">By:</span>
                                <span class="detail-value">{{ $selectedPayment->processor->name ?? 'Admin' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button wire:click="closeModals" class="btn-secondary">
                        Close
                    </button>
                    @if($selectedPayment->isPending())
                    <div class="action-group">
                        <button wire:click="openApproveModal({{ $selectedPayment->id }})" class="btn-success">
                            <i class="fas fa-check mr-2"></i>
                            Approve
                        </button>
                        <button wire:click="openRejectModal({{ $selectedPayment->id }})" class="btn-warning">
                            <i class="fas fa-times mr-2"></i>
                            Reject
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Approve Payment Modal -->
    @if($showApproveModal && $selectedPayment)
        <div class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <i class="fas fa-check-circle modal-icon success"></i>
                    <h3 class="modal-title">Approve Payment</h3>
                </div>
                <div class="modal-content">
                    <p class="modal-description">
                        Are you sure you want to approve this payment from 
                        <strong>{{ $selectedPayment->user->name }}</strong> for 
                        <strong>₱{{ number_format($selectedPayment->amount, 2) }}</strong>?
                    </p>
                    <div class="payment-summary">
                        <div class="summary-item">
                            <span>Method:</span>
                            <strong>{{ $selectedPayment->payment_method }}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Reference:</span>
                            <strong>{{ $selectedPayment->reference_number ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button wire:click="closeModals" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button wire:click="approvePayment({{ $selectedPayment->id }})" class="btn-success">
                        <i class="fas fa-check mr-2"></i>
                        Approve Payment
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Reject Payment Modal -->
    @if($showRejectModal && $selectedPayment)
        <div class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <i class="fas fa-times-circle modal-icon warning"></i>
                    <h3 class="modal-title">Reject Payment</h3>
                </div>
                <div class="modal-content">
                    <p class="modal-description">
                        Please provide a reason for rejecting this payment from 
                        <strong>{{ $selectedPayment->user->name }}</strong>.
                    </p>
                    <div class="form-group">
                        <label for="rejectionReason" class="form-label">Rejection Reason *</label>
                        <textarea wire:model="rejectionReason" 
                                  id="rejectionReason"
                                  class="form-textarea"
                                  placeholder="Explain why this payment is being rejected..."
                                  rows="4"></textarea>
                        @error('rejectionReason') 
                            <span class="error-message">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>
                <div class="modal-actions">
                    <button wire:click="closeModals" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button wire:click="rejectPayment" class="btn-warning">
                        <i class="fas fa-ban mr-2"></i>
                        Reject Payment
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Method Modal -->
    @if($showMethodModal)
        <div class="modal-overlay">
            <div class="modal-container large">
                <div class="modal-header">
                    <i class="fas fa-cog modal-icon"></i>
                    <h3 class="modal-title">
                        {{ $editingMethod ? 'Edit Payment Method' : 'Add New Payment Method' }}
                    </h3>
                    <button wire:click="closeMethodModal" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveMethod">
                    <div class="modal-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="methodName" class="form-label">Method Name *</label>
                                <input type="text" 
                                       wire:model="methodForm.name"
                                       id="methodName"
                                       class="form-input"
                                       placeholder="e.g., GCash, PayPal, Bank Transfer">
                                @error('methodForm.name') 
                                    <span class="error-message">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="methodType" class="form-label">Type *</label>
                                <select wire:model="methodForm.type" id="methodType" class="form-select">
                                    <option value="qr_code">QR Code</option>
                                    <option value="email">Email</option>
                                    <option value="bank_account">Bank Account</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('methodForm.type') 
                                    <span class="error-message">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sortOrder" class="form-label">Sort Order</label>
                                <input type="number" 
                                       wire:model="methodForm.sort_order"
                                       id="sortOrder"
                                       class="form-input"
                                       min="0">
                                @error('methodForm.sort_order') 
                                    <span class="error-message">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="instructions" class="form-label">Instructions</label>
                                <textarea wire:model="methodForm.instructions"
                                          id="instructions"
                                          class="form-textarea"
                                          placeholder="Provide instructions for students on how to use this payment method..."
                                          rows="3"></textarea>
                                @error('methodForm.instructions') 
                                    <span class="error-message">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Conditional fields based on type -->
                            @if($methodForm['type'] === 'qr_code')
                                <div class="form-group full-width">
                                    <label for="qrCodeImage" class="form-label">
                                        QR Code Image {{ !$editingMethod ? '*' : '' }}
                                    </label>
                                    <input type="file" 
                                           wire:model="qrCodeImage"
                                           id="qrCodeImage"
                                           class="form-input"
                                           accept="image/*">
                                    @error('qrCodeImage') 
                                        <span class="error-message">{{ $message }}</span> 
                                    @enderror
                                    @if($editingMethod && $editingMethod->qr_code_image)
                                        <div class="current-image">
                                            <span>Current QR Code:</span>
                                            <img src="{{ Storage::url($editingMethod->qr_code_image) }}" 
                                                 alt="Current QR Code"
                                                 class="current-qr-image">
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($methodForm['type'] === 'email')
                                <div class="form-group full-width">
                                    <label for="methodEmail" class="form-label">Email Address *</label>
                                    <input type="email" 
                                           wire:model="methodForm.email"
                                           id="methodEmail"
                                           class="form-input"
                                           placeholder="payments@yourbusiness.com">
                                    @error('methodForm.email') 
                                        <span class="error-message">{{ $message }}</span> 
                                    @enderror
                                </div>
                            @endif

                            @if($methodForm['type'] === 'bank_account')
                                <div class="form-group">
                                    <label for="accountName" class="form-label">Account Name *</label>
                                    <input type="text" 
                                           wire:model="methodForm.account_name"
                                           id="accountName"
                                           class="form-input"
                                           placeholder="Your Business Name">
                                    @error('methodForm.account_name') 
                                        <span class="error-message">{{ $message }}</span> 
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="accountNumber" class="form-label">Account Number *</label>
                                    <input type="text" 
                                           wire:model="methodForm.account_number"
                                           id="accountNumber"
                                           class="form-input"
                                           placeholder="XX-XXXX-XXXX-XXXX">
                                    @error('methodForm.account_number') 
                                        <span class="error-message">{{ $message }}</span> 
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" 
                                           wire:model="methodForm.is_active"
                                           class="checkbox-input">
                                    <span class="checkbox-text">Active (available for students)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" wire:click="closeMethodModal" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            {{ $editingMethod ? 'Update' : 'Create' }} Method
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>