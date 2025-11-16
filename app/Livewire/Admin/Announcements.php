<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Announcement;

class Announcements extends Component
{
    // You can make this a public property if needed for reactivity
    public $announcements;

    public function mount()
    {
        // Fetch latest 5 announcements
        $this->announcements = Announcement::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.announcements', [
            'announcements' => $this->announcements,
        ]);
    }
}
