<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Payments extends Component
{
    public function render()
    {
        return view('livewire.admin.payments')->layout('layouts.app');
    }
}
