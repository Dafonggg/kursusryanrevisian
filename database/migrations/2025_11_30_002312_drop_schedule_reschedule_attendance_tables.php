<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop tables yang memiliki foreign keys terlebih dahulu
        Schema::dropIfExists('session_reminder_logs');
        Schema::dropIfExists('reschedule_requests');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('course_sessions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migration ini tidak bisa di-reverse karena struktur table tidak disimpan
        // Jika perlu rollback, harus restore dari backup database
    }
};
