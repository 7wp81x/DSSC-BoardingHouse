<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Payments extends Component
{
    use WithPagination, WithFileUploads;

    public $currentTab = 'pending'; // pending, history, methods
    public $search = '';
    public $statusFilter = '';
    public $methodFilter = '';
    public $studentFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    // Payment action modals
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $showViewModal = false;
    public $selectedPayment = null;
    public $rejectionReason = '';

    // Payment methods management
    public $showMethodModal = false;
    public $editingMethod = null;
    public $methodForm = [
        'name' => '',
        'type' => 'qr_code',
        'instructions' => '',
        'account_name' => '',
        'account_number' => '',
        'email' => '',
        'qr_code_image' => null,
        'is_active' => true,
        'sort_order' => 0
    ];
    public $qrCodeImage;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'methodFilter' => ['except' => ''],
        'studentFilter' => ['except' => ''],
        'currentTab' => ['except' => 'pending']
    ];

    public function mount()
    {
        // Initialize default payment methods if none exist
        if (PaymentMethod::count() === 0) {
            $this->initializeDefaultMethods();
        }
    }

    private function initializeDefaultMethods()
    {
        $defaultMethods = [
            [
                'name' => 'GCash',
                'type' => 'qr_code',
                'instructions' => 'Scan the QR code below to pay via GCash. Upload your receipt after payment.',
                'account_name' => 'Your Business Name',
                'account_number' => '09XX XXX XXXX',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'PayPal',
                'type' => 'email',
                'instructions' => 'Send payment to our PayPal email address. Include your name and room number in the notes.',
                'email' => 'payments@yourbusiness.com',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Wise',
                'type' => 'bank_account',
                'instructions' => 'Transfer funds to our Wise account. Use your name as reference.',
                'account_name' => 'Your Business Name',
                'account_number' => 'XX-XXXX-XXXX-XXXX',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($defaultMethods as $method) {
            PaymentMethod::create($method);
        }
    }

    public function switchTab($tab)
    {
        $this->currentTab = $tab;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->methodFilter = '';
        $this->studentFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    // Payment Actions
    public function viewPayment($paymentId)
    {
        $this->selectedPayment = Payment::with(['user', 'bill.room'])->find($paymentId);
        $this->showViewModal = true;
    }

    public function approvePayment($paymentId)
    {
        $payment = Payment::find($paymentId);
        
        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => Payment::STATUS_APPROVED,
                'processed_by' => auth()->id(),
                'processed_at' => now()
            ]);

            // Here you can also update the bill status if needed
            if ($payment->bill) {
                $payment->bill->update(['status' => 'paid']);
            }
        });

        $this->showApproveModal = false;
        $this->selectedPayment = null;
        
        session()->flash('message', 'Payment approved successfully.');
    }

    public function rejectPayment()
    {
        $this->validate([
            'rejectionReason' => 'required|min:5|max:500'
        ]);

        DB::transaction(function () {
            $this->selectedPayment->update([
                'status' => Payment::STATUS_REJECTED,
                'rejection_reason' => $this->rejectionReason,
                'processed_by' => auth()->id(),
                'processed_at' => now()
            ]);
        });

        $this->showRejectModal = false;
        $this->selectedPayment = null;
        $this->rejectionReason = '';
        
        session()->flash('message', 'Payment rejected successfully.');
    }

    public function cancelPayment($paymentId)
    {
        $payment = Payment::find($paymentId);
        $payment->update([
            'status' => Payment::STATUS_CANCELLED,
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        session()->flash('message', 'Payment cancelled successfully.');
    }

    // Payment Methods Management
    public function showMethodForm($methodId = null)
    {
        $this->editingMethod = $methodId ? PaymentMethod::find($methodId) : null;
        
        if ($this->editingMethod) {
            $this->methodForm = $this->editingMethod->toArray();
        } else {
            $this->methodForm = [
                'name' => '',
                'type' => 'qr_code',
                'instructions' => '',
                'account_name' => '',
                'account_number' => '',
                'email' => '',
                'qr_code_image' => null,
                'is_active' => true,
                'sort_order' => PaymentMethod::max('sort_order') + 1
            ];
        }
        
        $this->showMethodModal = true;
    }

    public function saveMethod()
    {
        $rules = [
            'methodForm.name' => 'required|string|max:255',
            'methodForm.type' => 'required|in:qr_code,email,bank_account,other',
            'methodForm.instructions' => 'nullable|string|max:1000',
            'methodForm.account_name' => 'nullable|string|max:255',
            'methodForm.account_number' => 'nullable|string|max:255',
            'methodForm.email' => 'nullable|email|max:255',
            'methodForm.sort_order' => 'required|integer|min:0',
            'qrCodeImage' => 'nullable|image|max:2048'
        ];

        // Conditional validation based on type
        if ($this->methodForm['type'] === 'qr_code') {
            if (!$this->editingMethod) {
                $rules['qrCodeImage'] = 'required|image|max:2048';
            }
        } elseif ($this->methodForm['type'] === 'email') {
            $rules['methodForm.email'] = 'required|email|max:255';
        } elseif ($this->methodForm['type'] === 'bank_account') {
            $rules['methodForm.account_name'] = 'required|string|max:255';
            $rules['methodForm.account_number'] = 'required|string|max:255';
        }

        $this->validate($rules);

        DB::transaction(function () {
            $data = $this->methodForm;

            // Handle QR code image upload
            if ($this->qrCodeImage) {
                $path = $this->qrCodeImage->store('payment-methods', 'public');
                $data['qr_code_image'] = $path;
                
                // Delete old image if exists
                if ($this->editingMethod && $this->editingMethod->qr_code_image) {
                    Storage::disk('public')->delete($this->editingMethod->qr_code_image);
                }
            }

            if ($this->editingMethod) {
                $this->editingMethod->update($data);
            } else {
                PaymentMethod::create($data);
            }
        });

        $this->closeMethodModal();
        session()->flash('message', 'Payment method saved successfully.');
    }

    public function toggleMethodStatus($methodId)
    {
        $method = PaymentMethod::find($methodId);
        $method->update(['is_active' => !$method->is_active]);
        
        session()->flash('message', 'Payment method status updated.');
    }

    public function deleteMethod($methodId)
    {
        $method = PaymentMethod::find($methodId);
        
        // Check if method is being used
        if ($method->payments()->exists()) {
            session()->flash('error', 'Cannot delete payment method that has associated payments.');
            return;
        }

        // Delete QR code image if exists
        if ($method->qr_code_image) {
            Storage::disk('public')->delete($method->qr_code_image);
        }

        $method->delete();
        session()->flash('message', 'Payment method deleted successfully.');
    }

    public function closeMethodModal()
    {
        $this->showMethodModal = false;
        $this->editingMethod = null;
        $this->methodForm = [
            'name' => '',
            'type' => 'qr_code',
            'instructions' => '',
            'account_name' => '',
            'account_number' => '',
            'email' => '',
            'qr_code_image' => null,
            'is_active' => true,
            'sort_order' => 0
        ];
        $this->qrCodeImage = null;
    }

    // Modal handlers
    public function openApproveModal($paymentId)
    {
        $this->selectedPayment = Payment::find($paymentId);
        $this->showApproveModal = true;
    }

    public function openRejectModal($paymentId)
    {
        $this->selectedPayment = Payment::find($paymentId);
        $this->showRejectModal = true;
    }

    public function closeModals()
    {
        $this->showApproveModal = false;
        $this->showRejectModal = false;
        $this->showViewModal = false;
        $this->selectedPayment = null;
        $this->rejectionReason = '';
    }

    // Data getters
    public function getPaymentsQuery()
    {
        $query = Payment::with(['user', 'bill.room', 'creator'])
            ->when($this->currentTab === 'pending', function ($q) {
                return $q->pending();
            })
            ->when($this->currentTab === 'history', function ($q) {
                return $q->where('status', '!=', Payment::STATUS_PENDING);
            })
            ->when($this->search, function ($q) {
                return $q->where(function ($query) {
                    $query->where('reference_number', 'like', '%' . $this->search . '%')
                          ->orWhereHas('user', function ($userQuery) {
                              $userQuery->where('name', 'like', '%' . $this->search . '%')
                                       ->orWhere('email', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->statusFilter, function ($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->when($this->methodFilter, function ($q) {
                return $q->where('payment_method', $this->methodFilter);
            })
            ->when($this->studentFilter, function ($q) {
                return $q->where('user_id', $this->studentFilter);
            })
            ->when($this->dateFrom && $this->dateTo, function ($q) {
                return $q->whereBetween('created_at', [
                    $this->dateFrom . ' 00:00:00',
                    $this->dateTo . ' 23:59:59'
                ]);
            })
            ->latest();

        return $query;
    }

    public function getPaymentsProperty()
    {
        return $this->getPaymentsQuery()->paginate(10);
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::ordered()->get();
    }

    public function getStudentsProperty()
    {
        return User::where('role', 'student')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    public function getStatusCountsProperty()
    {
        return [
            'pending' => Payment::pending()->count(),
            'approved' => Payment::approved()->count(),
            'rejected' => Payment::rejected()->count(),
            'cancelled' => Payment::cancelled()->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.payments', [
            'payments' => $this->payments,
            'paymentMethods' => $this->payment_methods,
            'students' => $this->students,
            'statusCounts' => $this->status_counts
        ]) ->layout('layouts.app');
    }
}