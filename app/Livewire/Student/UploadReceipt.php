<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Payment;

class UploadReceipt extends Component
{
    use WithFileUploads;

    public $booking;
    public $receipt;
    public $reference = '';
    public $uploading = false;

    public function mount($booking)
    {
        $this->booking = $booking;
    }

    public function upload()
    {
        $this->validate([
            'receipt'   => 'required|image|mimes:jpg,jpeg,png,pdf|max:5048', // 5MB max
            'reference' => 'required|string|max:255',
        ]);

        $path = $this->receipt->store('receipts', 'public');

        Payment::create([
            'booking_id'       => $this->booking->id,
            'amount'           => $this->booking->room->price,
            'payment_method'   => 'manual',
            'reference_number' => $this->reference,
            'receipt_image'    => $path,
            'status'           => 'pending_verification',
            'paid_at'          => now(),
            'created_by'       => auth()->id(),
        ]);

        $this->reset(['receipt', 'reference']);
        $this->dispatch('receipt-uploaded');
        session()->flash('success', 'Receipt uploaded! Admin will verify soon.');
    }

    public function render()
    {
        return view('livewire.student.upload-receipt');
    }
}