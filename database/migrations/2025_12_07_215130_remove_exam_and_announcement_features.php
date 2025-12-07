<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration removes the exam and announcement features:
     * - Drops exam_submissions table (must be first due to foreign key)
     * - Drops exams table
     * - Drops announcements table
     * - Removes score column from certificates table
     * - Removes description column from course_materials table
     */
    public function up(): void
    {
        // Drop exam_submissions first (has foreign key to exams)
        Schema::dropIfExists('exam_submissions');
        
        // Drop exams table
        Schema::dropIfExists('exams');
        
        // Drop announcements table
        Schema::dropIfExists('announcements');
        
        // Remove score column from certificates table
        if (Schema::hasColumn('certificates', 'score')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropColumn('score');
            });
        }
        
        // Remove description column from course_materials table
        if (Schema::hasColumn('course_materials', 'description')) {
            Schema::table('course_materials', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     * This would restore the tables and columns if needed.
     */
    public function down(): void
    {
        // Add back description column to course_materials
        Schema::table('course_materials', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
        });
        
        // Add back score column to certificates
        Schema::table('certificates', function (Blueprint $table) {
            $table->decimal('score', 5, 2)->nullable()->after('file_path');
        });
        
        // Recreate announcements table
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
        
        // Recreate exams table
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('order')->default(1);
            $table->timestamps();
        });
        
        // Recreate exam_submissions table
        Schema::create('exam_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->decimal('score', 5, 2)->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('graded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['exam_id', 'enrollment_id']);
        });
    }
};
