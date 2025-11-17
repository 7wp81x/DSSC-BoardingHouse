<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            // Add only the missing columns
            $table->foreignId('user_id')->after('booking_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('rent')->after('amount');
            $table->text('description')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'type', 'description']);
        });
    }
};