<?php

namespace App\Livewire\Student;

use App\Models\MaintenanceRequest;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Maintenance extends Component
{
    public $requests;

    public function mount()
    {
        $this->requests = Auth::user()
            ->maintenanceRequests()
            ->with(['room'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.student.maintenance')
            ->layout('layouts.app'); // ← THIS IS THE KEY LINE
    }
}