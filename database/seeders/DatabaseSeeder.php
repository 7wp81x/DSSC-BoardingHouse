<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\StudentBoarder;  // New: For auto-creating boarders

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate existing tables (add StudentBoarder if needed)
        User::truncate();
        Room::truncate();
        Booking::truncate();
        StudentBoarder::truncate();  // New: Clear boarders

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles
        $adminRole = Role::create(['name' => 'admin']);
        $studentRole = Role::create(['name' => 'student']);

        // Admin
        $admin = User::create([
            'name' => 'Admin DSSC',
            'email' => 'admin@dssc.edu.ph',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'admin',
            'gender' => 'male',
            'phone' => '09123456789',
        ]);
        $admin->assignRole('admin');

        // 12 Students with genders
        $maleNames = ['Juan Dela Cruz', 'Pedro Santos', 'Miguel Reyes', 'Carlos Lopez', 'Antonio Garcia', 'Jose Martinez'];
        $femaleNames = ['Maria Santos', 'Ana Reyes', 'Elena Garcia', 'Carmen Lopez', 'Sofia Martinez', 'Isabel Rodriguez'];
        
        $allStudents = [];
        
        // Create male students
        foreach ($maleNames as $index => $name) {
            $student = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@dssc.edu.ph',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'student',
                'student_id' => '2024-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'gender' => 'male',
                'phone' => '09' . rand(100000000, 999999999),
                'emergency_contact' => 'Parent of ' . $name,
                'emergency_phone' => '09' . rand(100000000, 999999999),
            ]);
            $student->assignRole('student');
            $allStudents[] = $student;
        }
        
        // Create female students
        foreach ($femaleNames as $index => $name) {
            $student = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@dssc.edu.ph',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'student',
                'student_id' => '2024-' . str_pad($index + 7, 4, '0', STR_PAD_LEFT),
                'gender' => 'female',
                'phone' => '09' . rand(100000000, 999999999),
                'emergency_contact' => 'Parent of ' . $name,
                'emergency_phone' => '09' . rand(100000000, 999999999),
            ]);
            $student->assignRole('student');
            $allStudents[] = $student;
        }

        // 18 Rooms with random amenities
        $allAmenities = ['WiFi', 'Aircon', 'Private CR', 'Cabinet', 'Study Table', 'Hot Shower', 'Refrigerator', 'Electric Fan', 'Window View'];
        $types = ['single', 'twin', 'quad', 'premium'];

        foreach (range(101, 118) as $i) {
            $amenities = collect($allAmenities)->shuffle()->take(rand(3, 7))->values()->toArray();

            Room::create([
                'room_code' => 'R' . $i,  // Prefix for clarity
                'type' => $types[array_rand($types)],
                'price' => rand(45, 150) * 100,
                'status' => 'available',
                'amenities' => $amenities,
            ]);
        }

                // 8 Active Bookings - mix of male and female students
        $availableRooms = Room::where('status', 'available')->inRandomOrder()->take(8)->get();
        $studentsToBook = collect($allStudents)->shuffle()->take(8);

        foreach ($studentsToBook as $index => $student) {
            if ($availableRooms->count() > $index) {
                $room = $availableRooms[$index];
                $room->update(['status' => 'occupied']);

                $booking = Booking::create([
                    'user_id' => $student->id,
                    'room_id' => $room->id,
                    'check_in_date' => now()->subDays(rand(10, 90)),
                    'monthly_due_date' => 5,  // ← Integer for 5th of month
                    'status' => 'active',
                ]);

                // Auto-create approved StudentBoarder for active bookings
                $boarder = $booking->studentBoarder()->create([
                    'user_id' => $student->id,
                    'approval_status' => 'approved',
                    'room_assignment_notes' => 'Seeded active boarding',
                ]);

                // Sync next due (one overdue for testing red highlight)
                $nextDue = now()->startOfMonth()->addDays(4);
                if ($index === 0) {  // First one overdue
                    $nextDue = now()->subDays(10);
                }
                $boarder->update(['next_payment_due' => $nextDue]);
            }
        }

        // 4 Pending Requests (for remaining students)
        $remainingStudents = collect($allStudents)->slice(8, 4);
        $remainingRooms = Room::where('status', 'available')->inRandomOrder()->take(4)->get();

        foreach ($remainingStudents as $index => $student) {
            if ($remainingRooms->count() > $index) {
                $room = $remainingRooms[$index];
                $room->update(['status' => 'maintenance']);  // Hold for request

                $booking = Booking::create([
                    'user_id' => $student->id,
                    'room_id' => $room->id,
                    'check_in_date' => now()->addDays(1),
                    'monthly_due_date' => 5,  // ← Integer for 5th of month
                    'status' => 'pending',
                ]);

                // Auto-create pending StudentBoarder
                $booking->studentBoarder()->create([
                    'user_id' => $student->id,
                    'approval_status' => 'pending',
                    'room_assignment_notes' => 'Seeded pending request from ' . $student->name,
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@dssc.edu.ph / password');
        $this->command->info('Students created: 6 male, 6 female');
        $this->command->info('Total rooms: 18 (8 occupied, 4 maintenance/pending, 6 available)');
        $this->command->info('Active StudentBoarders: 8 (1 overdue for testing)');
        $this->command->info('Pending StudentBoarders: 4 (visible in admin dashboard)');
    }
}