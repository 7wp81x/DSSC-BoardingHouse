<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_boarders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('next_payment_due')->nullable();
            $table->text('room_assignment_notes')->nullable();
            $table->boolean('kick_notice_sent')->default(false);
            $table->softDeletes();  // For deleted_at column
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_boarders');
    }
};