<?php

namespace App\Livewire\Admin;

use App\Models\Announcement;
use App\Models\User;  // ← Added import
use Livewire\Component;

class CreateAnnouncement extends Component
{
    public $title = '';
    public $content = '';
    public $is_pinned = false;
    public $published_at;
    public $directed_to_user_id = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'is_pinned' => 'boolean',
        'published_at' => 'nullable|date',
        'directed_to_user_id' => 'nullable|exists:users,id',
    ];

    public function mount()
    {
        $this->directed_to_user_id = request()->query('directed_to');
        $this->published_at = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        Announcement::create([
            'title' => $this->title,
            'content' => $this->content,
            'is_pinned' => $this->is_pinned,
            'published_at' => $this->published_at,
            'created_by' => auth()->id(),
            'directed_to_user_id' => $this->directed_to_user_id,
        ]);

        session()->flash('message', 'Announcement created and sent successfully!');

        return redirect()->route('admin.announcements.index');
    }

    public function render()
    {
        $students = User::where('role', 'student')->get();

        return view('livewire.admin.create-announcement', compact('students'))
            ->layout('layouts.app');
    }
}