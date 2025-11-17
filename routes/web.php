<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// ROOT ROUTE — THE FIX
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(
        auth()->user()->role === 'admin' ? 'admin.dashboard' : 'student.dashboard'
    );
})->name('home');

// PROFILE ROUTES (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Settings route
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
});

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        
        // Rooms routes
        Route::get('/rooms', \App\Livewire\Admin\Rooms::class)->name('rooms');
        Route::get('/rooms/create', \App\Livewire\Admin\CreateRoom::class)->name('rooms.create');
        Route::get('/rooms/{room}/edit', \App\Livewire\Admin\EditRoom::class)->name('rooms.edit');
        
        Route::get('/student-boarders', \App\Livewire\Admin\StudentBoarders::class)->name('student-boarders.index');
        Route::get('/student-boarders/{studentBoarder}', \App\Livewire\Admin\ShowStudentBoarder::class)->name('student-boarders.show');
        Route::get('/payments', \App\Livewire\Admin\Payments::class)->name('payments');
        Route::get('/maintenance', \App\Livewire\Admin\Maintenance::class)->name('maintenance');
        Route::get('/announcements', \App\Livewire\Admin\CreateAnnouncement::class)->name('announcements.index');
    });


Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Student\Dashboard::class)->name('dashboard');
        Route::get('/room', \App\Livewire\Student\Room::class)->name('room');
        Route::get('/room/{room}', \App\Livewire\Student\ViewRoom::class)->name('room.view'); // ← Add this line
        Route::get('/payments', \App\Livewire\Student\Payments::class)->name('payments');
        Route::get('/maintenance', \App\Livewire\Student\Maintenance::class)->name('maintenance');
    });

require __DIR__.'/auth.php';