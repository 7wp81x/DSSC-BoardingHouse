<?php

namespace App\Livewire\Admin;

use App\Models\StudentBoarder;
use Livewire\Component;

class ShowStudentBoarder extends Component
{
    public StudentBoarder $studentBoarder;

    public function mount(StudentBoarder $studentBoarder)
    {
        $this->studentBoarder = $studentBoarder->load([
            'user', 
            'booking.room', 
            'currentBill', 
            'directedAnnouncements'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.show-student-boarder')
            ->layout('layouts.app');
    }
}