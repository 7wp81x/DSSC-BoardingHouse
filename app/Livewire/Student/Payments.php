<?php
// Student Payment Component
namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Bill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Payments extends Component
{
    use WithFileUploads;

    public $currentTab = 'make-payment';
    public $bills = [];
    public $paymentMethods = [];

    // Payment form
    public $selectedBill = null;
    public $selectedMethod = null;
    public $referenceNumber = '';
    public $receiptImage = null;
    public $amount = 0;

    // Modals
    public $showPaymentModal = false;
    public $showReceiptModal = false;
    public $selectedPayment = null;

    public function mount()
    {
        $this->loadBills();
        $this->paymentMethods = PaymentMethod::active()->ordered()->get();
    }

    private function loadBills()
    {
        $this->bills = Bill::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'overdue', 'pending_payment'])
            ->with(['booking.room'])
            ->get();
    }

    public function switchTab($tab)
    {
        $this->currentTab = $tab;
        if ($tab === 'make-payment') {
            $this->loadBills();
        }
    }

    public function selectBill($billId)
    {
        $this->selectedBill = Bill::with(['booking.room'])->find($billId);
        $this->amount = $this->selectedBill->amount;
    }

    public function selectMethod($methodId)
    {
        $this->selectedMethod = PaymentMethod::find($methodId);
    }

    // ADD THIS METHOD TO FIX THE ERROR
    public function validateAmount()
    {
        // This method is called when the amount changes
        // You can add custom validation logic here if needed
        // For now, we'll just let Livewire handle the validation
    }

    public function openPaymentModal()
    {
        if (!$this->selectedBill) {
            session()->flash('error', 'Please select a bill to pay.');
            return;
        }
        if (!$this->selectedMethod) {
            session()->flash('error', 'Please select a payment method.');
            return;
        }

        $this->showPaymentModal = true;
    }

    public function submitPayment()
    {
        $this->validate([
            'selectedBill' => 'required',
            'selectedMethod' => 'required',
            'referenceNumber' => 'required|string|max:255',
            'receiptImage' => 'required|image|max:2048',
            'amount' => 'required|numeric|min:0.01|max:' . ($this->selectedBill->amount * 1.1) // Allow 10% overpayment
        ]);

        try {
            DB::transaction(function () {
                // Upload receipt image
                $receiptPath = $this->receiptImage->store('payment-receipts', 'public');

                // Create payment
                $payment = Payment::create([
                    'bill_id' => $this->selectedBill->id,
                    'user_id' => auth()->id(),
                    'amount' => $this->amount,
                    'payment_method' => $this->selectedMethod->name,
                    'reference_number' => $this->referenceNumber,
                    'receipt_image' => $receiptPath,
                    'status' => Payment::STATUS_PENDING,
                    'created_by' => auth()->id(),
                    'paid_at' => now()
                ]);

                // Update bill status to pending_payment
                $this->selectedBill->update(['status' => 'pending_payment']);

                \Log::info('Payment created', [
                    'payment_id' => $payment->id,
                    'user_id' => auth()->id(),
                    'bill_id' => $this->selectedBill->id,
                    'amount' => $this->amount
                ]);
            });

            $this->resetPaymentForm();
            $this->showPaymentModal = false;
            $this->loadBills();

            session()->flash('message', 'Payment submitted successfully! It will be reviewed by admin.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error submitting payment: ' . $e->getMessage());
            \Log::error('Payment submission error', ['error' => $e->getMessage()]);
        }
    }

    private function resetPaymentForm()
    {
        $this->selectedBill = null;
        $this->selectedMethod = null;
        $this->referenceNumber = '';
        $this->receiptImage = null;
        $this->amount = 0;
    }

    public function viewReceipt($paymentId)
    {
        $this->selectedPayment = Payment::with(['bill.booking.room'])->find($paymentId);
        $this->showReceiptModal = true;
    }

    public function closeModals()
    {
        $this->showPaymentModal = false;
        $this->showReceiptModal = false;
        $this->selectedPayment = null;
    }

    public function getPaymentsHistoryProperty()
    {
        return Payment::where('user_id', auth()->id())
            ->with(['bill.booking.room'])
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.student.payments', [
            'paymentsHistory' => $this->paymentsHistory,
        ])->layout('layouts.app');
    }
}