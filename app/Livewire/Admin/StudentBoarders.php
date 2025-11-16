<?php

namespace App\Livewire\Admin;

use App\Models\StudentBoarder;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class StudentBoarders extends Component
{
    use WithPagination;

    public $tab = 'non-boarders';
    public $search = '';
    public $showApproveModal = false;
    public $showConfirmModal = false;
    public $actionToConfirm;
    public $studentIdToConfirm;
    public $selectedStudentId;
    public $selectedDueDate = null;
    public $perPage = 10;

    protected $queryString = ['tab', 'perPage'];

    public function updatedTab()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function confirmApprove($studentId)
    {
        $this->selectedStudentId = $studentId;
        
        // Default to today, or fetch check-in date for context
        $student = StudentBoarder::find($studentId);
        $defaultDate = $student->booking->check_in_date ?? now();
        $this->selectedDueDate = $defaultDate->format('Y-m-d');
        
        $this->showApproveModal = true;
    }

    public function closeModal()
    {
        $this->showApproveModal = false;
        $this->selectedDueDate = null;
    }

    public function approve()
    {
        $this->validate([
            'selectedDueDate' => 'required|date|after_or_equal:today',
        ]);

        $student = StudentBoarder::findOrFail($this->selectedStudentId);
        if ($student->approval_status === 'pending') {
            // Extract day from selected date (e.g., '2025-11-05' → 5)
            $dueDay = Carbon::parse($this->selectedDueDate)->day;
            
            // Update booking with monthly_due_date (reliable recurring day)
            $student->booking->update([
                'monthly_due_date' => $dueDay,
                'status' => 'active',
            ]);
            
            // Update student status (triggers boot() to set next_payment_due based on monthly_due_date)
            $student->update(['approval_status' => 'approved']);
            
            // Manually occupy room (boot might not cover this)
            $student->booking->room->update(['status' => 'occupied']);

            session()->flash('message', "Student approved! Monthly due set to the {$dueDay}th. Next payment: " . $student->next_payment_due->format('M d, Y'));
        }

        $this->closeModal();
        $this->resetPage();  // Refresh table
    }

    public function confirmAction($action, $studentId)
    {
        $this->actionToConfirm = $action;
        $this->studentIdToConfirm = $studentId;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
    }

    public function performAction()
    {
        if ($this->actionToConfirm === 'delete') {
            $this->delete($this->studentIdToConfirm);
        } elseif ($this->actionToConfirm === 'remove') {
            $this->removeFromTrashed($this->studentIdToConfirm);
        }

        $this->closeConfirmModal();
        $this->resetPage();
    }

    public function delete($studentId)  // ← Public method for soft delete
    {
        $student = StudentBoarder::findOrFail($studentId);
        if ($student->approval_status === 'approved') {
            $student->delete();  // Soft delete to trash

            session()->flash('message', 'Student moved to trash.');
        }
        $this->resetPage();
    }

    public function removeFromTrashed($studentId)
    {
        $student = StudentBoarder::withTrashed()->findOrFail($studentId);
        if ($student->trashed()) {
            $student->booking->room->update(['status' => 'available']);
            $student->booking->delete();
            $student->forceDelete();

            session()->flash('message', 'Student unassigned from room. User account kept.');
        }
        $this->resetPage();
    }

    public function restore($studentId)
    {
        $student = StudentBoarder::withTrashed()->findOrFail($studentId);
        if ($student->trashed()) {
            $student->restore();
            $student->booking->room->update(['status' => 'occupied']);

            session()->flash('message', 'Student restored.');
        }
        $this->resetPage();
    }

    public function render()
    {
        $pendingCount = StudentBoarder::pending()->count();
        $approvedCount = StudentBoarder::approved()->count();
        $trashedCount = StudentBoarder::onlyTrashed()->count();
        $nonBoardersCount = User::where('role', 'student')
            ->whereDoesntHave('studentBoarder')
            ->whereDoesntHave('studentBoarder', function ($q) {
                $q->withTrashed();
            })
            ->count();

        $students = collect();

        if ($this->tab === 'non-boarders') {
            $query = User::where('role', 'student')
                ->whereDoesntHave('studentBoarder')
                ->whereDoesntHave('studentBoarder', function ($q) {
                    $q->withTrashed();
                })
                ->when($this->search, fn($q) => $q->where(fn($sub) => $sub
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")));
            $students = $query->paginate($this->perPage);
        } else {
            $query = StudentBoarder::with(['user', 'booking.room', 'currentBill','booking.bills', 'directedAnnouncements'])
                ->when($this->tab === 'pending', fn($q) => $q->pending())
                ->when($this->tab === 'approved', fn($q) => $q->approved())
                ->when($this->tab === 'trashed', fn($q) => $q->onlyTrashed())
                ->when($this->search, fn($q) => $q->whereHas('user', fn($sub) => $sub
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")));

            $students = $query->paginate($this->perPage);
        }

        return view('livewire.admin.student-boarders', compact('students', 'pendingCount', 'approvedCount', 'trashedCount', 'nonBoardersCount')) ->layout('layouts.app');
    }
}