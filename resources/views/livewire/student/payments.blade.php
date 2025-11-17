<div class="student-payments-container">
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
                        <h1 class="header-title">Student Payments</h1>
                        <p class="header-subtitle">Manage your bills and payment history</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs-container">
            <nav class="tabs-nav">
                <button wire:click="switchTab('make-payment')" 
                        class="tab-button {{ $currentTab === 'make-payment' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-credit-card tab-icon"></i>
                    <span>Make Payment</span>
                    @if($bills->count() > 0)
                        <span class="tab-count badge-pending">{{ $bills->count() }}</span>
                    @endif
                </button>
                <button wire:click="switchTab('history')" 
                        class="tab-button {{ $currentTab === 'history' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-history tab-icon"></i>
                    <span>Payment History</span>
                </button>
            </nav>
        </div>

        <!-- Make Payment Tab -->
        @if($currentTab === 'make-payment')
        <div class="payment-section">
            <h2 class="section-title">Make a Payment</h2>

            <!-- Select Bill -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">1. Select Bill to Pay</h3>
                <div class="bills-grid">
                    @forelse($bills as $bill)
                        <div class="bill-card {{ $selectedBill && $selectedBill->id === $bill->id ? 'selected' : '' }}"
                             wire:click="selectBill({{ $bill->id }})">
                            <div class="bill-header">
                                <div>
                                    <div class="bill-title">Bill #{{ $bill->id }}</div>
                                    <div class="bill-room">
                                        @if($bill->booking && $bill->booking->room)
                                            Room {{ $bill->booking->room->room_code }}
                                        @endif
                                    </div>
                                </div>
                                <div class="bill-amount">₱{{ number_format($bill->amount, 2) }}</div>
                            </div>
                            <div class="bill-details">
                                <div>Due Date: {{ $bill->due_date->format('M d, Y') }}</div>
                                <div>Type: {{ ucfirst($bill->type) }}</div>
                                <div>Status: <span class="status-badge status-{{ $bill->status }}">{{ ucfirst($bill->status) }}</span></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full empty-state">
                            <div class="empty-content">
                                <i class="fas fa-receipt empty-icon"></i>
                                <h3 class="empty-title">No Bills Found</h3>
                                <p class="empty-description">You don't have any pending bills at the moment.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Select Payment Method -->
            @if($selectedBill)
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">2. Choose Payment Method</h3>
                <div class="methods-grid">
                    @foreach($paymentMethods as $method)
                        <div class="method-card {{ $selectedMethod && $selectedMethod->id === $method->id ? 'selected' : '' }}"
                             wire:click="selectMethod({{ $method->id }})">
                            <div class="method-header">
                                <i class="fas {{ $method->type === 'qr_code' ? 'fa-qrcode' : ($method->type === 'email' ? 'fa-envelope' : 'fa-university') }} method-icon"></i>
                                <div class="method-name">{{ $method->name }}</div>
                            </div>
                            <div class="method-details">
                                @if($method->account_name)
                                    <div class="detail-item">
                                        <span class="detail-label">Account:</span>
                                        <span class="detail-value">{{ $method->account_name }}</span>
                                    </div>
                                @endif
                                @if($method->account_number)
                                    <div class="detail-item">
                                        <span class="detail-label">Number:</span>
                                        <span class="detail-value">{{ $method->account_number }}</span>
                                    </div>
                                @endif
                                @if($method->email)
                                    <div class="detail-item">
                                        <span class="detail-label">Email:</span>
                                        <span class="detail-value">{{ $method->email }}</span>
                                    </div>
                                @endif
                            </div>
                            @if($method->instructions)
                                <div class="method-instructions">
                                    <strong>Instructions:</strong> {{ $method->instructions }}
                                </div>
                            @endif
                            @if($method->qr_code_image)
                                <div class="mt-3 text-center">
                                    <img src="{{ Storage::url($method->qr_code_image) }}" 
                                         alt="{{ $method->name }} QR Code" 
                                         class="qr-image"
                                         style="max-width: 200px; height: auto; margin: 0 auto;">
                                    <div class="text-xs text-muted mt-2">Scan QR Code to Pay</div>
                                    <!-- Removed View Full Size link to prevent issues -->
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Payment Details -->
            @if($selectedBill && $selectedMethod)
            <div class="payment-form">
                <h3 class="text-lg font-semibold mb-4">3. Payment Details</h3>
                
                <div class="form-group">
                    <label class="form-label">Reference Number *</label>
                    <input type="text" 
                           wire:model="referenceNumber"
                           class="form-input"
                           placeholder="Enter transaction reference number">
                    @error('referenceNumber') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Amount (₱) *</label>
                    <input type="number" 
                           wire:model="amount"
                           class="form-input {{ $amount > $selectedBill->amount ? 'border-red-500' : ($amount < $selectedBill->amount ? 'border-yellow-500' : 'border-green-500') }}"
                           step="0.01"
                           min="0.01"
                           max="{{ $selectedBill->amount * 1.1 }}"
                           placeholder="0.00"
                           wire:change="validateAmount">
                    @error('amount') <span class="error-message">{{ $message }}</span> @enderror
                    
                    <!-- Amount validation messages -->
                    @if($amount > 0)
                        <div class="mt-2 text-sm">
                            @if($amount > $selectedBill->amount)
                                <div class="text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Overpayment: ₱{{ number_format($amount - $selectedBill->amount, 2) }} 
                                    (Bill: ₱{{ number_format($selectedBill->amount, 2) }})
                                </div>
                                <div class="text-red-500 text-xs mt-1">
                                    Note: Overpayments will be credited to your account
                                </div>
                            @elseif($amount < $selectedBill->amount)
                                <div class="text-yellow-600 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Partial payment: ₱{{ number_format($selectedBill->amount - $amount, 2) }} remaining
                                </div>
                                <div class="text-yellow-500 text-xs mt-1">
                                    Note: Partial payments require admin approval
                                </div>
                            @else
                                <div class="text-green-600 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Full payment amount
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Upload Receipt *</label>
                    <div class="file-input-wrapper">
                        <input type="file" 
                               wire:model="receiptImage"
                               class="file-input"
                               id="receiptImage"
                               accept="image/*">
                        <label for="receiptImage" class="file-input-label">
                            <i class="fas fa-upload mr-2"></i>
                            Choose Receipt Image
                        </label>
                    </div>
                    @if($receiptImage)
                        <div class="file-name mt-2">
                            <i class="fas fa-file-image text-success mr-2"></i>
                            {{ $receiptImage->getClientOriginalName() }}
                        </div>
                    @endif
                    @error('receiptImage') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Payment Summary -->
                <div class="payment-summary">
                    <h4 class="font-semibold mb-3">Payment Summary</h4>
                    <div class="summary-item">
                        <span>Bill Amount:</span>
                        <span>₱{{ number_format($selectedBill->amount, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span>Payment Method:</span>
                        <span>{{ $selectedMethod->name }}</span>
                    </div>
                    <div class="summary-item {{ $amount != $selectedBill->amount ? 'text-yellow-600 font-semibold' : '' }}">
                        <span>Amount to Pay:</span>
                        <span>₱{{ number_format($amount, 2) }}</span>
                    </div>
                    @if($amount != $selectedBill->amount)
                    <div class="summary-item text-sm {{ $amount > $selectedBill->amount ? 'text-red-600' : 'text-yellow-600' }}">
                        <span>Difference:</span>
                        <span>
                            {{ $amount > $selectedBill->amount ? '+' : '-' }}₱{{ number_format(abs($amount - $selectedBill->amount), 2) }}
                        </span>
                    </div>
                    @endif
                </div>

                <button wire:click="openPaymentModal" 
                        class="btn-primary mt-4 w-full py-3 text-lg"
                        {{ !$amount || !$referenceNumber || !$receiptImage ? 'disabled' : '' }}
                        style="{{ !$amount || !$referenceNumber || !$receiptImage ? 'opacity: 0.6; cursor: not-allowed;' : '' }}">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Payment for Review
                </button>
            </div>
            @endif
        </div>
        @endif

        <!-- Payment History Tab -->
        @if($currentTab === 'history')
        <div class="payment-section">
            <h2 class="section-title">Payment History</h2>
            
            <div class="table-wrapper">
                <table class="payments-table">
                    <thead class="table-header">
                        <tr>
                            <th class="table-th">Bill #</th>
                            <th class="table-th">Amount</th>
                            <th class="table-th">Method</th>
                            <th class="table-th">Reference</th>
                            <th class="table-th">Date</th>
                            <th class="table-th">Status</th>
                            <th class="table-th">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($paymentsHistory as $payment)
                            <tr class="table-row status-{{ $payment->status }}">
                                <td class="table-td">{{ $payment->bill_id ?? 'N/A' }}</td>
                                <td class="table-td">
                                    <div class="amount-display">
                                        <span class="amount-value">₱{{ number_format($payment->amount, 2) }}</span>
                                        @if($payment->bill && $payment->amount != $payment->bill->amount)
                                            <div class="text-xs {{ $payment->amount > $payment->bill->amount ? 'text-red-500' : 'text-yellow-500' }}">
                                                {{ $payment->amount > $payment->bill->amount ? 'Overpaid' : 'Partial' }}
                                            </div>
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
                                </td>
                                <td class="table-td">
                                    <div class="action-buttons">
                                        <button wire:click="viewReceipt({{ $payment->id }})" 
                                                class="btn-action primary"
                                                title="View Receipt">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="table-row">
                                <td colspan="7" class="table-td empty-state">
                                    <div class="empty-content">
                                        <i class="fas fa-history empty-icon"></i>
                                        <h3 class="empty-title">No Payment History</h3>
                                        <p class="empty-description">You haven't made any payments yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($paymentsHistory->hasPages())
                <div class="pagination-container">
                    {{ $paymentsHistory->links() }}
                </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Submit Payment Modal -->
    @if($showPaymentModal)
        <div class="modal-overlay" style="display: flex; align-items: center; justify-content: center; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000;">
            <div class="modal-container" style="background: white; border-radius: 12px; padding: 0; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                <div class="modal-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-paper-plane modal-icon" style="color: #4f46e5;"></i>
                    <h3 class="modal-title" style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Confirm Payment Submission</h3>
                </div>
                <div class="modal-content" style="padding: 1.5rem;">
                    <p class="modal-description" style="color: #6b7280; margin-bottom: 1.5rem;">
                        Please confirm your payment details below. This payment will be reviewed by administration.
                    </p>
                    <div class="payment-summary" style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                        <div class="summary-item" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #6b7280;">Bill:</span>
                            <strong style="color: #1f2937;">#{{ $selectedBill->id }}</strong>
                        </div>
                        <div class="summary-item" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #6b7280;">Amount:</span>
                            <strong style="color: {{ $amount != $selectedBill->amount ? '#f59e0b' : '#1f2937' }};">₱{{ number_format($amount, 2) }}</strong>
                        </div>
                        @if($amount != $selectedBill->amount)
                        <div class="summary-item" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #6b7280;">Bill Amount:</span>
                            <span style="color: #6b7280;">₱{{ number_format($selectedBill->amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="summary-item" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #6b7280;">Method:</span>
                            <strong style="color: #1f2937;">{{ $selectedMethod->name }}</strong>
                        </div>
                        <div class="summary-item" style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">Reference:</span>
                            <strong style="color: #1f2937;">{{ $referenceNumber }}</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-actions" style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; gap: 0.75rem; justify-content: flex-end;">
                    <button wire:click="closeModals" class="btn-secondary" style="background: #f3f4f6; color: #374151; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 500;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Go Back
                    </button>
                    <button wire:click="submitPayment" class="btn-success" style="background: #10b981; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 500;">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Payment
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- View Receipt Modal -->
    @if($showReceiptModal && $selectedPayment)
        <div class="modal-overlay" style="display: flex; align-items: center; justify-content: center; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000;">
            <div class="modal-container large" style="background: white; border-radius: 12px; padding: 0; max-width: 800px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                <div class="modal-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: between;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                        <i class="fas fa-receipt modal-icon" style="color: #4f46e5;"></i>
                        <h3 class="modal-title" style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Payment Receipt</h3>
                    </div>
                    <button wire:click="closeModals" class="modal-close" style="background: none; border: none; cursor: pointer; padding: 0.5rem; border-radius: 4px; color: #6b7280;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-content" style="padding: 1.5rem;">
                    <div class="payment-details-grid" style="display: grid; gap: 1.5rem;">
                        <div class="detail-section">
                            <h4 class="section-title" style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Payment Information</h4>
                            <div class="detail-item" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span class="detail-label" style="color: #6b7280;">Amount:</span>
                                <span class="detail-value" style="color: #1f2937; font-weight: 500;">₱{{ number_format($selectedPayment->amount, 2) }}</span>
                            </div>
                            <div class="detail-item" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span class="detail-label" style="color: #6b7280;">Method:</span>
                                <span class="detail-value" style="color: #1f2937; font-weight: 500;">{{ $selectedPayment->payment_method }}</span>
                            </div>
                            <div class="detail-item" style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span class="detail-label" style="color: #6b7280;">Reference:</span>
                                <span class="detail-value" style="color: #1f2937; font-weight: 500;">{{ $selectedPayment->reference_number ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item" style="display: flex; justify-content: space-between;">
                                <span class="detail-label" style="color: #6b7280;">Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge status-{{ $selectedPayment->status }}" style="display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                        {{ ucfirst($selectedPayment->status) }}
                                    </span>
                                </span>
                            </div>
                        </div>

                        @if($selectedPayment->receipt_image)
                        <div class="detail-section full-width">
                            <h4 class="section-title" style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Receipt Image</h4>
                            <div class="receipt-preview" style="text-align: center;">
                                <img src="{{ Storage::url($selectedPayment->receipt_image) }}" 
                                     alt="Payment Receipt"
                                     class="receipt-image" 
                                     style="max-width: 100%; max-height: 400px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                <a href="{{ Storage::url($selectedPayment->receipt_image) }}" 
                                   target="_blank" 
                                   class="btn-action primary mt-3" 
                                   style="display: inline-flex; align-items: center; background: #4f46e5; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.875rem;">
                                    <i class="fas fa-expand mr-2"></i>
                                    View Full Size
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($selectedPayment->rejection_reason)
                        <div class="detail-section full-width">
                            <h4 class="section-title" style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Rejection Reason</h4>
                            <div class="rejection-reason" style="background: #fef3c7; padding: 1rem; border-radius: 8px; border-left: 4px solid #f59e0b;">
                                <i class="fas fa-exclamation-triangle mr-2" style="color: #f59e0b;"></i>
                                {{ $selectedPayment->rejection_reason }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-actions" style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end;">
                    <button wire:click="closeModals" class="btn-secondary" style="background: #f3f4f6; color: #374151; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 500;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>