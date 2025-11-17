<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LandingPageController extends Controller
{
    public function landing()
    {
        // Get available rooms with their primary images
        $rooms = Room::where('status', 'available')
                    ->with(['images' => function($query) {
                        $query->where('is_primary', 1);
                    }])
                    ->orderBy('type')
                    ->limit(4)
                    ->get();

        // Get room types and their min prices for pricing section
        $roomTypes = Room::where('status', 'available')
                        ->selectRaw('type, MIN(price) as min_price, COUNT(*) as room_count')
                        ->groupBy('type')
                        ->get()
                        ->keyBy('type');
        
        return view('landing-page', compact('rooms', 'roomTypes'));
    }

    public function showRooms()
    {
        $rooms = Room::where('status', 'available')
                    ->with(['images' => function($query) {
                        $query->where('is_primary', 1)->orWhere('order', 0);
                    }])
                    ->get();
        
        return view('public.rooms', compact('rooms'));
    }

    public function showRoom(Room $room)
    {
        $room->load('images');
        $relatedRooms = Room::where('type', $room->type)
                           ->where('id', '!=', $room->id)
                           ->where('status', 'available')
                           ->with(['images' => function($query) {
                               $query->where('is_primary', 1);
                           }])
                           ->limit(3)
                           ->get();
        
        return view('public.room-detail', compact('room', 'relatedRooms'));
    }

    public function showPricing()
    {
        $roomTypes = Room::where('status', 'available')
                        ->selectRaw('type, MIN(price) as min_price, MAX(price) as max_price, AVG(price) as avg_price, COUNT(*) as room_count')
                        ->groupBy('type')
                        ->get()
                        ->keyBy('type');

        $allRooms = Room::where('status', 'available')
                       ->with(['images' => function($query) {
                           $query->where('is_primary', 1);
                       }])
                       ->get()
                       ->groupBy('type');

        return view('public.pricing', compact('roomTypes', 'allRooms'));
    }

    public function showContact()
    {
        return view('public.contact');
    }

    public function submitInquiry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'message' => 'required|string',
            'room_type' => 'nullable|string'
        ]);

        // Here you can:
        // 1. Send email notification
        // 2. Store in database
        // 3. Integrate with your CRM
        
        return back()->with('success', 'Thank you for your inquiry! We will contact you soon.');
    }
}
