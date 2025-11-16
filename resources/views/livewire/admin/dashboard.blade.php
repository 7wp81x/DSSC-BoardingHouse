<div class="p-8" >
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-12 text-center max-w-4xl mx-auto"style="padding: 20px;">
        <div class="welcome-icon mb-6">
            <svg class="crown-icon mx-auto" viewBox="0 0 24 24" fill="currentColor" width="80" height="80">
                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
            </svg>
        </div>
        
        <h1 class="text-5xl font-bold text-gray-800 dark:text-white mb-4">
            Welcome back, {{ auth()->user()->name }}!
        </h1>

        <div class="flex justify-between gap-6 mt-12">
    <!-- Total Rooms Card -->
    <div class="dashboard-card">
        <a href="{{ route('admin.payments') }}" class="block"> 
        <div class="card-header">
            <h3 class="card-title">Total Rooms</h3>
            <div class="card-icon room-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1a1 1 0 0 0 .7-1.7l-9-9a1 1 0 0 0-1.4 0l-9 9A1 1 0 0 0 3 13z"/>
                </svg>
            </div>
        </div>
        <p class="card-value">{{ \App\Models\Room::count() }}</p>
        <div class="quick-stats">
            <div class="stat-item">
                <p class="stat-value">{{ \App\Models\Room::where('status', 'available')->count() }}</p>
                <p class="stat-label">Available</p>
            </div>
            <div class="stat-item">
                <p class="stat-value">{{ \App\Models\Room::where('status', 'occupied')->count() }}</p>
                <p class="stat-label">Occupied</p>
            </div>
        </div>
    </a>
    </div>

    <!-- Occupied Rooms Card -->
    <div class="dashboard-card">
        <a href="{{ route('admin.payments') }}" class="block"> 
        <div class="card-header">
            <h3 class="card-title">Occupied Rooms</h3>
            <div class="card-icon users-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
        <p class="card-value">{{ \App\Models\Booking::where('status', 'active')->count() }}</p>
        <div class="quick-stats">
            <div class="stat-item">
                <p class="stat-value">
                    @php
                        $maleOccupied = \App\Models\Booking::where('status', 'active')
                            ->whereHas('user', function($q) { 
                                $q->where('gender', 'male'); 
                            })->count();
                    @endphp
                    {{ $maleOccupied }}
                </p>
                <p class="stat-label">Male</p>
            </div>
            <div class="stat-item">
                <p class="stat-value">
                    @php
                        $femaleOccupied = \App\Models\Booking::where('status', 'active')
                            ->whereHas('user', function($q) { 
                                $q->where('gender', 'female'); 
                            })->count();
                    @endphp
                    {{ $femaleOccupied }}
                </p>
                <p class="stat-label">Female</p>
            </div>
        </div>
        </a>
    </div>

    <!-- Students Card -->
   
    <div class="dashboard-card">
        <a href="{{ route('admin.payments') }}" class="block"> 
        <div class="card-header">
            <h3 class="card-title">Total Students</h3>
            <div class="card-icon student-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>
        <p class="card-value">{{ \App\Models\User::where('role', 'student')->count() }}</p>
        <div class="quick-stats">
            <div class="stat-item">
                <p class="stat-value">{{ \App\Models\User::where('role', 'student')->where('gender', 'male')->count() }}</p>
                <p class="stat-label">Male</p>
            </div>
            <div class="stat-item">
                <p class="stat-value">{{ \App\Models\User::where('role', 'student')->where('gender', 'female')->count() }}</p>
                <p class="stat-label">Female</p>
            </div>
        </div>
        <a>

    </div>

    <!-- Pending Payments Card -->
    <div class="dashboard-card">
        <a href="{{ route('admin.payments') }}" class="block">

        <div class="card-header">
            <h3 class="card-title">Pending Payments</h3>
            <div class="card-icon payment-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                </svg>
            </div>
        </div>
        <p class="card-value">
            @if(Schema::hasColumn('payments', 'status'))
                {{ \App\Models\Payment::where('status', 'pending')->count() }}
            @else
                {{ \App\Models\Payment::count() }}
            @endif
        </p>
        
        <div class="quick-stats">
            <div class="stat-item">
                <p class="stat-value">
                    @if(Schema::hasColumn('payments', 'status'))
                        {{ \App\Models\Payment::where('status', 'pending')->count() }}
                    @else
                        {{ \App\Models\Payment::count() }}
                    @endif
                </p>
                <p class="stat-label">All</p>
            </div>
            <div class="stat-item">
                <p class="stat-value">0</p>
                <p class="stat-label">Overdue</p>
            </div>
            </a>
        </div>
    </div>
</div>
        </div>
    </div>
</div>