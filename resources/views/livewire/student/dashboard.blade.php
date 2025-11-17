<div class="student-dashboard-container">
    <!-- Welcome Header -->
    <div class="main-card mb-8">
        <div class="card-header">
            <div class="header-content">
                <div class="header-title-section">
                    <i class="fas fa-home header-icon"></i>
                    <div>
                        <h1 class="header-title">Student Dashboard</h1>
                        <p class="header-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
                    </div>
                </div>
                <div class="header-stats">
                    <div class="stat-badge pending">
                        <span class="stat-number">{{ $pendingBillsCount }}</span>
                        <span class="stat-label">Pending Bills</span>
                    </div>
                    <div class="stat-badge approved">
                        <span class="stat-number">{{ $activeRoomsCount }}</span>
                        <span class="stat-label">Active Rooms</span>
                    </div>
                    <div class="stat-badge warning">
                        <span class="stat-number">{{ $pendingRoomsCount }}</span>
                        <span class="stat-label">Pending Requests</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($activeRoomsCount > 0)
        <!-- Room Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Active Rooms Summary Card -->
            <div class="main-card">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-door-open text-indigo-600 dark:text-indigo-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Your Rooms</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Active assignments</p>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $activeRoomsCount }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $activeRoomsCount === 1 ? 'Room Assigned' : 'Rooms Assigned' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Rent Card -->
            <div class="main-card">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Monthly Rent</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">All rooms combined</p>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            ₱{{ number_format($totalMonthlyRent, 2) }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Per month total
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Due Card -->
            <div class="main-card">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-pink-600 dark:text-pink-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Next Due</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Payment deadline</p>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $nextBill?->due_date?->format('M d') ?? 'No Due' }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            @if($nextBill)
                                {{ $nextBill->due_date->diffForHumans() }}
                            @else
                                No pending bills
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Room Cards -->
        <div class="main-card mb-8">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-list text-indigo-600 dark:text-indigo-400"></i>
                    Your Room Assignments
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activeBookings as $booking)
                        @if($booking->room)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Room {{ $booking->room->room_code }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($booking->room->type) }} Room</p>
                                </div>
                                <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 px-3 py-1 rounded-full text-sm font-medium">
                                    Active
                                </span>
                            </div>
                            
                            <div class="space-y-3">
                                <!-- Room Rent -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Monthly Rent:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">₱{{ number_format($booking->room->price, 2) }}</span>
                                </div>
                                
                                <!-- Roommates -->
                                @php
                                    $roommates = $booking->room->currentStudents->where('user.id', '!=', auth()->id());
                                @endphp
                                @if($roommates->count() > 0)
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Roommates ({{ $roommates->count() }}):</span>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($roommates as $student)
                                        <div class="flex items-center gap-3 p-2 bg-white dark:bg-gray-600 rounded-lg">
                                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                                {{ Str::substr($student->user->name, 0, 1) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $student->user->email }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-3 bg-white dark:bg-gray-600 rounded-lg">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No roommates assigned</p>
                                </div>
                                @endif
                                
                                <!-- Room Amenities -->
                                @if($booking->room->amenities && is_array($booking->room->amenities) && count($booking->room->amenities) > 0)
                                <div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 mb-2 block">Amenities:</span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($booking->room->amenities, 0, 4) as $amenity)
                                        <span class="bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs border border-gray-200 dark:border-gray-500">
                                            {{ $amenity }}
                                        </span>
                                        @endforeach
                                        @if(count($booking->room->amenities) > 4)
                                        <span class="bg-white dark:bg-gray-600 text-gray-500 dark:text-gray-400 px-2 py-1 rounded text-xs border border-gray-200 dark:border-gray-500">
                                            +{{ count($booking->room->amenities) - 4 }} more
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="main-card mb-8">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('student.payments') }}" class="flex items-center gap-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors border border-indigo-100 dark:border-indigo-800">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Make Payment</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pay your bills</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('student.room') }}" class="flex items-center gap-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors border border-green-100 dark:border-green-800">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Room Details</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">View room information</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('student.maintenance') }}" class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors border border-blue-100 dark:border-blue-800">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Maintenance</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Request repairs</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Pending Bookings -->
    @if($pendingRoomsCount > 0)
    <div class="main-card mb-8">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                Pending Room Requests
            </h3>
            <div class="space-y-4">
                @foreach($pendingBookings as $booking)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-semibold text-yellow-800 dark:text-yellow-200">
                                Room {{ $booking->room->room_code ?? 'N/A' }}
                                @if($booking->room)
                                    <span class="text-yellow-600 dark:text-yellow-300 text-sm">({{ ucfirst($booking->room->type) }} Room)</span>
                                @endif
                            </h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">Waiting for admin approval</p>
                        </div>
                        <span class="bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 px-3 py-1 rounded-full text-sm font-medium">
                            Pending
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- No Active Bookings State -->
    @if($activeRoomsCount == 0 && $pendingRoomsCount == 0)
        <div class="main-card">
            <div class="empty-state">
                <div class="empty-content">
                    <i class="fas fa-home empty-icon"></i>
                    <h3 class="empty-title">No Active Rooms</h3>
                    <p class="empty-description">
                        You are not currently assigned to any room. Please contact the administration to get assigned to a room.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Announcements Section -->
    <div class="main-card">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                    <i class="fas fa-bullhorn text-indigo-600 dark:text-indigo-400"></i>
                    Latest Announcements
                </h3>
                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-1 rounded-full text-sm font-medium">
                    {{ count($announcements) }} announcements
                </span>
            </div>
            
            <div class="space-y-4">
                @forelse($announcements as $announcement)
                    <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors group">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">
                                {{ $announcement->title }}
                            </h4>
                            @if($announcement->is_urgent)
                                <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs px-2 py-1 rounded-full font-medium flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Urgent
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 mb-3">
                            {{ $announcement->content }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-clock"></i>
                                {{ $announcement->created_at->diffForHumans() }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-user"></i>
                                By Administration
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-bullhorn text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h4 class="text-lg font-semibold text-gray-500 dark:text-gray-400 mb-2">No Announcements</h4>
                        <p class="text-gray-400 dark:text-gray-500">Check back later for updates from administration.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>