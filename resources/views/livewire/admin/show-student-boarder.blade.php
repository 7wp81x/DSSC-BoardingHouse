<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Boarder Details: {{ $studentBoarder->user->name }}</h2>
        <a href="{{ route('admin.student-boarders.index') }}" class="text-blue-600 hover:underline">Back to List</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium mb-2">Student Info</h3>
            <p><strong>Name:</strong> {{ $studentBoarder->user->name }}</p>
            <p><strong>Email:</strong> {{ $studentBoarder->user->email }}</p>
            <p><strong>Phone:</strong> {{ $studentBoarder->user->phone ?? 'N/A' }}</p>
            <p><strong>Student ID:</strong> {{ $studentBoarder->user->student_id ?? 'N/A' }}</p>
        </div>

        <div>
            <h3 class="text-lg font-medium mb-2">Boarding Details</h3>
            <p><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $studentBoarder->approval_status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($studentBoarder->approval_status) }}</span></p>
            <p><strong>Room:</strong> {{ $studentBoarder->room->room_code ?? 'N/A' }} ({{ $studentBoarder->room->type ?? 'N/A' }})</p>
            <p><strong>Check-in:</strong> {{ $studentBoarder->booking->check_in_date?->format('M d, Y') ?? 'N/A' }}</p>
            <p><strong>Monthly Due:</strong> {{ $studentBoarder->booking->monthly_due_date ?? 'Not Set' }}th</p>
            <p><strong>Next Payment:</strong> {{ $studentBoarder->next_payment_due?->format('M d, Y') ?? 'N/A' }}</p>
            @if($studentBoarder->room_assignment_notes)
                <p class="mt-2 p-2 bg-gray-50 rounded"><strong>Notes:</strong> {{ $studentBoarder->room_assignment_notes }}</p>
            @endif
        </div>

        <div class="md:col-span-2">
            <h3 class="text-lg font-medium mb-2">Notices</h3>
            @if($studentBoarder->directedAnnouncements->count() > 0)
                @foreach($studentBoarder->directedAnnouncements as $announcement)
                    <div class="border p-3 rounded mb-2">
                        <h4 class="font-semibold">{{ $announcement->title }}</h4>
                        <p>{{ $announcement->content }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">No directed notices.</p>
            @endif
        </div>
    </div>
</div>