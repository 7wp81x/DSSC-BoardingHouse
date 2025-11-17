<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\StudentBoarder;
use App\Models\MaintenanceRequest;
use App\Models\Announcement;

class Dashboard extends Component
{
    public $totalRooms;
    public $availableRooms;
    public $occupiedRooms;
    public $maintenanceRooms;
    public $totalStudents;
    public $maleStudents;
    public $femaleStudents;
    public $activeStudents;
    public $pendingPayments;
    public $approvedPayments;
    public $rejectedPayments;
    public $monthlyRevenue;
    public $collectedRevenue;
    public $pendingRevenue;
    public $occupancyRate;
    public $collectionRate;
    public $pendingApplications;
    public $maintenanceRequests;
    public $recentAnnouncements;
    public $recentPayments;

    public function mount()
    {
        $this->loadRoomStats();
        $this->loadStudentStats();
        $this->loadPaymentStats();
        $this->loadRevenueStats();
        $this->loadSystemStats();
        $this->loadRecentData();
    }

    private function loadRoomStats()
    {
        $this->totalRooms = Room::count();
        $this->availableRooms = Room::where('status', 'available')->count();
        $this->occupiedRooms = Room::where('status', 'occupied')->count();
        $this->maintenanceRooms = Room::where('status', 'maintenance')->count();
        
        $this->occupancyRate = $this->totalRooms > 0 
            ? round(($this->occupiedRooms / $this->totalRooms) * 100) 
            : 0;
    }

    private function loadStudentStats()
    {
        $this->totalStudents = User::where('role', 'student')->count();
        $this->maleStudents = User::where('role', 'student')->where('gender', 'male')->count();
        $this->femaleStudents = User::where('role', 'student')->where('gender', 'female')->count();
        $this->activeStudents = Booking::where('status', 'active')->count();
        $this->pendingApplications = StudentBoarder::where('approval_status', 'pending')->count();
    }

    private function loadPaymentStats()
    {
        $this->pendingPayments = Payment::where('status', 'pending')->count();
        $this->approvedPayments = Payment::where('status', 'approved')->count();
        $this->rejectedPayments = Payment::where('status', 'rejected')->count();
    }

    private function loadRevenueStats()
    {
        $currentMonth = now()->startOfMonth();
        
        $this->monthlyRevenue = Room::where('status', 'occupied')->sum('price');
        $this->collectedRevenue = Payment::where('status', 'approved')
            ->where('created_at', '>=', $currentMonth)
            ->sum('amount');
        $this->pendingRevenue = Payment::where('status', 'pending')
            ->where('created_at', '>=', $currentMonth)
            ->sum('amount');
            
        $this->collectionRate = $this->monthlyRevenue > 0 
            ? round(($this->collectedRevenue / $this->monthlyRevenue) * 100) 
            : 0;
    }

    private function loadSystemStats()
    {
        $this->maintenanceRequests = MaintenanceRequest::where('status', 'pending')->count();
        $this->recentAnnouncements = Announcement::where('created_at', '>=', now()->subDays(7))->count();
    }

    private function loadRecentData()
    {
        $this->recentPayments = Payment::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }
}