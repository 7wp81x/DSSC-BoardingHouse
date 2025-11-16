<div class="max-w-7xl mx-auto space-y-12">

    <!-- Flash Messages (New: For request feedback) -->
    @if (session()->has('message'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl mb-6">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- MY CURRENT ROOM (Updated: Shows pending as "Pending Approval") -->
    @if($myRoom)
        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl shadow-2xl overflow-hidden text-white">
            <div class="md:flex">
                <div class="md:w-1/3">
                    @if($myRoom->image)
                        <img src="{{ asset('storage/'.$myRoom->image) }}" class="w-full h-64 md:h-full object-cover">
                    @else
                        <div class="bg-white/20 h-64 md:h-full flex items-center justify-center">
                            <span class="text-6xl font-bold">{{ $myRoom->room_code }}</span>
                        </div>
                    @endif
                </div>
                <div class="p-8 md:p-12 flex-1">
                    <h1 class="text-4xl font-bold mb-4">
                        @if($myRoom->bookings->where('status', 'pending')->first())
                            Pending Approval!
                        @else
                            Welcome Home!
                        @endif
                    </h1>
                    <div class="grid grid-cols-2 gap-6 text-lg">
                        <div>
                            <p class="opacity-90">Your Room</p>
                            <p class="text-3xl font-bold">{{ $myRoom->room_code }}</p>
                        </div>
                        <div>
                            <p class="opacity-90">Monthly Rent</p>
                            <p class="text-3xl font-bold">₱{{ number_format($myRoom->price) }}</p>
                        </div>
                    </div>
                    <div class="mt-8">
                        <p class="text-xl">Amenities:</p>
                        <div class="flex flex-wrap gap-3 mt-3">
                            @foreach($myRoom->amenities ?? [] as $amenity)
                                <span class="px-4 py-2 bg-white/20 rounded-full">{{ ucwords(str_replace('_', ' ', $amenity)) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-2 border-dashed border-yellow-300 dark:border-yellow-600 rounded-3xl p-16 text-center">
            <i class="fas fa-home text-8xl text-yellow-500 mb-6"></i>
            <h2 class="text-3xl font-bold text-yellow-800 dark:text-yellow-200">No Room Booked Yet</h2>
            <p class="text-xl mt-4">Choose your perfect room below and make it yours!</p>
        </div>
    @endif

    <!-- AVAILABLE ROOMS -->
{{-- Available Rooms Section --}}
@if(!empty($availableRooms['available']))

    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Available Rooms</h2>
            <flux-select wire:model.live="selectedType" class="w-48">
                <option value="all">All Types</option>
                <option value="single">Single</option>
                <option value="twin">Twin</option>
                <option value="quad">Quad</option>
                <option value="premium">Premium</option>
            </flux-select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($availableRooms['available'] as $room)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border hover:shadow-2xl transition">
                    
                    @if(!empty($room['image']))
                        <img src="{{ asset('storage/'.$room['image']) }}" class="w-full h-48 object-cover">
                    @else
                        <div class="bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 h-48 flex items-center justify-center">
                            <span class="text-5xl font-bold text-gray-500">{{ $room['room_code'] }}</span>
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-2xl font-bold">{{ $room['room_code'] }}</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">₱{{ number_format($room['price']) }}/mo</p>
                        <p class="text-sm text-gray-600 mt-1 capitalize">{{ $room['type'] }} Room</p>

                        <div class="mt-4">
                            <p class="text-sm font-semibold mb-2">Amenities:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($room['amenities'] ?? [] as $a)
                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">
                                        {{ ucwords(str_replace('_',' ',$a)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6">
                            <flux-button 
                                class="w-full" 
                                variant="primary"
                                wire:click="requestBoarding({{ $room['id'] }})"
                                wire:loading.attr="disabled">
                                Request Boarding
                            </flux-button>
                        </div>
                    </div>
                </div>
            @empty

                <div class="col-span-full text-center py-16">
                    <p class="text-xl text-gray-500">No rooms available in this category right now.</p>
                </div>
            @endforelse
        </div>
    </div>
@endif

{{-- Other Status Sections (Occupied, Full, Maintenance) --}}
@php
    $roomSections = [
        'occupied' => 'Occupied Rooms',
        'full' => 'Full Rooms',
        'maintenance' => 'Under Maintenance',
    ];
@endphp

@foreach($roomSections as $status => $title)
    @if(!empty($availableRooms[$status] ?? []))

        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-4">{{ $title }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($availableRooms[$status] as $room)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border hover:shadow-2xl transition">

                    @if(!empty($room['image']))
                        <img src="{{ asset('storage/'.$room['image']) }}" class="w-full h-48 object-cover">
                    @else
                        <div class="bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 h-48 flex items-center justify-center">
                            <span class="text-5xl font-bold text-gray-500">{{ $room['room_code'] }}</span>
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-2xl font-bold">{{ $room['room_code'] }}</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">₱{{ number_format($room['price']) }}/mo</p>
                        <p class="text-sm text-gray-600 mt-1 capitalize">{{ $room['type'] }} Room</p>

                        {{-- Status Badge --}}
                        <div class="mt-2">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                bg-{{ $status === 'occupied' ? 'blue' : ($status === 'full' ? 'red' : 'yellow') }}-100 
                                text-{{ $status === 'occupied' ? 'blue' : ($status === 'full' ? 'red' : 'yellow') }}-800">
                                {{ ucfirst($status) }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm font-semibold mb-2">Amenities:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($room['amenities'] ?? [] as $a)
                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">
                                        {{ ucwords(str_replace('_',' ',$a)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-500">Not available for booking</p>
                        </div>
                    </div>
                </div>
            @endforeach

            </div>
        </div>
    @endif
@endforeach
</div>