<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// LANDING PAGE ROUTE - Show landing page for guests only
Route::get('/', function () {
    // Fetch rooms data
    $rooms = \App\Models\Room::where('status', 'available')
                ->with(['images' => function($query) {
                    $query->where('is_primary', 1);
                }])
                ->orderBy('type')
                ->limit(4)
                ->get();

    // Get room types for pricing section
    $roomTypes = \App\Models\Room::where('status', 'available')
                    ->selectRaw('type, MIN(price) as min_price, COUNT(*) as room_count')
                    ->groupBy('type')
                    ->get()
                    ->keyBy('type');

    return view('landing-page', compact('rooms', 'roomTypes'));
})->name('home');

// AUTH ROUTES
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// PUBLIC PAGES (accessible to everyone)
Route::get('/rooms', [LandingPageController::class, 'showRooms'])->name('rooms.public');
Route::get('/rooms/{room}', [LandingPageController::class, 'showRoom'])->name('rooms.public.show');
Route::get('/pricing', [LandingPageController::class, 'showPricing'])->name('pricing.public');
Route::get('/contact', [LandingPageController::class, 'showContact'])->name('contact.public');

// INQUIRY FORM
Route::post('/inquiry', [LandingPageController::class, 'submitInquiry'])->name('inquiry.submit');

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
        Route::get('/room/{room}', \App\Livewire\Student\ViewRoom::class)->name('room.view');
        Route::get('/payments', \App\Livewire\Student\Payments::class)->name('payments');
        Route::get('/maintenance', \App\Livewire\Student\Maintenance::class)->name('maintenance');
    });

Route::get('/test-styles', function() {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <link href="https://cdn.tailwindcss.com/3.4.17" rel="stylesheet">
    </head>
    <body class="bg-blue-500 p-8">
        <div class="bg-white rounded-lg p-6 shadow-lg">
            <h1 class="text-3xl font-bold text-blue-600">Tailwind Test</h1>
            <p class="text-gray-600 mt-2">If this has styles, Tailwind is working!</p>
            <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Test Button</button>
        </div>
    </body>
    </html>
    ';
});
require __DIR__.'/auth.php';
