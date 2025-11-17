<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add only the missing columns
            $table->text('rejection_reason')->nullable()->after('status');
            $table->foreignId('processed_by')->nullable()->constrained('users')->after('created_by');
            $table->timestamp('processed_at')->nullable()->after('processed_by');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['rejection_reason', 'processed_by', 'processed_at']);
        });
    }
};