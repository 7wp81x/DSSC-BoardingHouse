<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Maintenance extends Component
{
    public function render()
    {
        return view('livewire.admin.maintenance')->layout('layouts.app');
    }
}
