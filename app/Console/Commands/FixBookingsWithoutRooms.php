<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Room;

class FixBookingsWithoutRooms extends Command
{
    protected $signature = 'bookings:fix-missing-rooms';
    protected $description = 'Assign rooms to bookings that are missing them';

    public function handle()
    {
        $bookingsWithoutRooms = Booking::where(function($query) {
            $query->whereDoesntHave('room')
                  ->orWhereNull('room_id');
        })->where('status', 'active')->get();

        $this->info("Found {$bookingsWithoutRooms->count()} active bookings without rooms.");

        if ($bookingsWithoutRooms->count() > 0) {
            $availableRoom = Room::where('status', 'available')->first();
            
            if ($availableRoom) {
                foreach ($bookingsWithoutRooms as $booking) {
                    $booking->update(['room_id' => $availableRoom->id]);
                    $this->info("Assigned booking {$booking->id} to room {$availableRoom->room_code}");
                }
                $this->info("Fixed all bookings.");
            } else {
                $this->error("No available rooms found!");
            }
        }
    }
}