<div class="admin-dashboard-container">
    <!-- Welcome Header -->
    <div class="main-card mb-8">
        <div class="card-header">
            <div class="header-content">
                <div class="header-title-section">
                    <i class="fas fa-crown header-icon"></i>
                    <div>
                        <h1 class="header-title">Admin Dashboard</h1>
                        <p class="header-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
                    </div>
                </div>
                <div class="header-stats">
                    <div class="stat-badge total">
                        <span class="stat-number">{{ $totalRooms }}</span>
                        <span class="stat-label">Total Rooms</span>
                    </div>
                    <div class="stat-badge approved">
                        <span class="stat-number">{{ $activeStudents }}</span>
                        <span class="stat-label">Active Students</span>
                    </div>
                    <div class="stat-badge pending">
                        <span class="stat-number">{{ $pendingPayments }}</span>
                        <span class="stat-label">Pending Payments</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Total Rooms Card -->
        <a href="{{ route('admin.rooms') }}" class="dashboard-card group">
            <div class="card-content">
                <div class="card-header-inner">
                    <div class="card-icon bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-home text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="card-info">
                        <h3 class="card-title">Total Rooms</h3>
                        <p class="card-value">{{ $totalRooms }}</p>
                    </div>
                </div>
                <div class="card-stats">
                    <div class="stat-item">
                        <span class="stat-value text-green-600">{{ $availableRooms }}</span>
                        <span class="stat-label">Available</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-blue-600">{{ $occupiedRooms }}</span>
                        <span class="stat-label">Occupied</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-amber-600">{{ $maintenanceRooms }}</span>
                        <span class="stat-label">Maintenance</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span class="view-link">Manage Rooms <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Students Card -->
        <a href="{{ route('admin.student-boarders.index') }}" class="dashboard-card group">
            <div class="card-content">
                <div class="card-header-inner">
                    <div class="card-icon bg-green-100 dark:bg-green-900">
                        <i class="fas fa-users text-green-600 dark:text-green-400"></i>
                    </div>
                    <div class="card-info">
                        <h3 class="card-title">Students</h3>
                        <p class="card-value">{{ $totalStudents }}</p>
                    </div>
                </div>
                <div class="card-stats">
                    <div class="stat-item">
                        <span class="stat-value text-blue-600">{{ $maleStudents }}</span>
                        <span class="stat-label">Male</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-pink-600">{{ $femaleStudents }}</span>
                        <span class="stat-label">Female</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-green-600">{{ $activeStudents }}</span>
                        <span class="stat-label">Active</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span class="view-link">Manage Students <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Payments Card -->
        <a href="{{ route('admin.payments') }}" class="dashboard-card group">
            <div class="card-content">
                <div class="card-header-inner">
                    <div class="card-icon bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-credit-card text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div class="card-info">
                        <h3 class="card-title">Payments</h3>
                        <p class="card-value">{{ $pendingPayments }}</p>
                    </div>
                </div>
                <div class="card-stats">
                    <div class="stat-item">
                        <span class="stat-value text-yellow-600">{{ $pendingPayments }}</span>
                        <span class="stat-label">Pending</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-green-600">{{ $approvedPayments }}</span>
                        <span class="stat-label">Approved</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-red-600">{{ $rejectedPayments }}</span>
                        <span class="stat-label">Rejected</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span class="view-link">View Payments <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Revenue Card -->
        <div class="dashboard-card">
            <div class="card-content">
                <div class="card-header-inner">
                    <div class="card-icon bg-indigo-100 dark:bg-indigo-900">
                        <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div class="card-info">
                        <h3 class="card-title">Monthly Revenue</h3>
                        <p class="card-value">₱{{ number_format($monthlyRevenue, 2) }}</p>
                    </div>
                </div>
                <div class="card-stats">
                    <div class="stat-item">
                        <span class="stat-value text-green-600">₱{{ number_format($collectedRevenue, 2) }}</span>
                        <span class="stat-label">Collected</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-yellow-600">₱{{ number_format($pendingRevenue, 2) }}</span>
                        <span class="stat-label">Pending</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value text-blue-600">{{ $occupancyRate }}%</span>
                        <span class="stat-label">Occupancy</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span class="text-sm text-gray-500 dark:text-gray-400">Current Month</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="main-card">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-bolt text-yellow-500"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.rooms.create') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-blue-100 dark:bg-blue-900">
                            <i class="fas fa-plus text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="quick-action-text">
                            <span>Add New Room</span>
                            <span>Create new room listing</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <a href="{{ route('admin.student-boarders.index') }}?tab=pending" class="quick-action-btn">
                        <div class="quick-action-icon bg-yellow-100 dark:bg-yellow-900">
                            <i class="fas fa-user-clock text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="quick-action-text">
                            <span>Pending Applications</span>
                            <span>{{ $pendingApplications }} waiting</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <a href="{{ route('admin.payments') }}?currentTab=pending" class="quick-action-btn">
                        <div class="quick-action-icon bg-purple-100 dark:bg-purple-900">
                            <i class="fas fa-credit-card text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="quick-action-text">
                            <span>Review Payments</span>
                            <span>{{ $pendingPayments }} pending</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <a href="{{ route('admin.announcements.index') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-green-100 dark:bg-green-900">
                            <i class="fas fa-bullhorn text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="quick-action-text">
                            <span>Create Announcement</span>
                            <span>Send notice to students</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="main-card">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-clock text-indigo-500"></i>
                    Recent Pending Payments
                </h3>
                <div class="space-y-3">
                    @forelse($recentPayments as $payment)
                    <div class="recent-payment-item">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-yellow-600 dark:text-yellow-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $payment->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">₱{{ number_format($payment->amount, 2) }} • {{ $payment->payment_method }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">
                            {{ $payment->created_at->diffForHumans() }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No pending payments</p>
                    </div>
                    @endforelse
                </div>
                @if($recentPayments->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('admin.payments') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 text-sm font-medium flex items-center gap-1">
                        View all payments
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- System Status -->
        <div class="main-card">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-chart-bar text-green-500"></i>
                    System Overview
                </h3>
                <div class="space-y-4">
                    <div class="system-stat">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Room Occupancy</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $occupancyRate }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill green" style="width: {{ $occupancyRate }}%"></div>
                        </div>
                    </div>

                    <div class="system-stat">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Payment Collection</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $collectionRate }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill blue" style="width: {{ $collectionRate }}%"></div>
                        </div>
                    </div>

                    <div class="system-stat">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Student Satisfaction</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">94%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill purple" style="width: 94%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $maintenanceRequests }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Maintenance Requests</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $recentAnnouncements }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Announcements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>