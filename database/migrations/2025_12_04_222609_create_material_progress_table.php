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
        Schema::create('material_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('course_materials')->cascadeOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Unique constraint: satu enrollment hanya bisa complete satu materi sekali
            $table->unique(['enrollment_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_progress');
    }
};
